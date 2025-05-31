<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Internship;
use App\Models\Application;
use Twilio\Rest\Client;

class EmployerInternshipController extends Controller
{
    public function index()
    {
        $internships = Internship::with('employer')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'internships' => $internships,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'duration' => 'required|string|max:100',
            'stipend' => 'nullable|string|max:100',
            'application_deadline' => 'nullable|date',
        ]);

        $employer = $request->user();

        $internship = Internship::create([
            'employer_id' => $employer->id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'duration' => $request->duration,
            'stipend' => $request->stipend,
            'application_deadline' => $request->application_deadline,
        ]);

        return response()->json([
            'message' => 'Internship created successfully',
            'internship' => $internship,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $employer = $request->user();

        $internship = Internship::with(['applications.student'])
            ->where('employer_id', $employer->id)
            ->findOrFail($id);

        return response()->json($internship);
    }

    // Update internship (only if it belongs to employer)
    public function update(Request $request, $id)
    {
        $employer = $request->user();

        $internship = Internship::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'location' => 'sometimes|string|max:255',
            'duration' => 'sometimes|string|max:100',
            'stipend' => 'nullable|string|max:100',
            'application_deadline' => 'nullable|date',
        ]);

        $internship->update($request->all());

        return response()->json([
            'message' => 'Internship updated successfully',
            'internship' => $internship,
        ]);
    }

    public function updateApplicationStatus(Request $request, $applicationId)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected',
    ]);

    $employer = $request->user();

    $application = \App\Models\Application::with('student', 'internship')->findOrFail($applicationId);

    // Ensure the employer owns the internship
    if ($application->internship->employer_id !== $employer->id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Prevent duplicate update
    if ($application->status === $request->status) {
        return response()->json([
            'message' => 'Status is already set to "' . $request->status . '"',
            'application' => $application
        ], 200);
    }

    $application->status = $request->status;
    $application->save();

    // --- SMS Notification ---
    $student = $application->student;
    $studentPhone = $student->phone; // Make sure this field exists and is in E.164 format

    if ($studentPhone) {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioFrom = env('TWILIO_VERIFY_SID'); // Or use a Twilio phone number

        $client = new Client($twilioSid, $twilioToken);

        $statusText = $request->status === 'accepted' ? 'accepted' : 'rejected';
        $message = "Your application for the internship '{$application->internship->title}' has been {$statusText}.";

         try {
            $client->messages->create(
                $studentPhone,
                [
                    'from' => $twilioFrom,
                    'body' => $message,
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Twilio SMS Error: ' . $e->getMessage());
        }
    }
    // --- End SMS Notification ---

    return response()->json([
        'message' => 'Application status updated successfully.',
        'application' => $application
    ]);
}





    public function destroy(Request $request, $id)
    {
        $employer = $request->user();

        $internship = Internship::findOrFail($id);

        $internship->delete();

        return response()->json(['message' => 'Internship deleted successfully']);
    }
}
