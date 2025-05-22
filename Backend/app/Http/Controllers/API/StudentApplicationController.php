<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentApplicationController extends Controller
{
// List all applications of authenticated student
public function index(Request $request)
{
$student = $request->user();

$applications = Application::with('internship')
->where('student_id', $student->id)
->get();

return response()->json($applications);
}




// Apply for internship
public function store(Request $request)
{
$student = $request->user();

$validator = Validator::make($request->all(), [
'internship_id' => 'required|exists:internships,id',
'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
'additional_notes' => 'nullable|string',
]);

if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

// Upload files
$resumePath = $request->file('resume')->store('uploads/resumes', 'public');
$cvPath = $request->file('cv')->store('uploads/cvs', 'public');

$application = Application::create([
'student_id' => $student->id,
'internship_id' => $request->internship_id,
'resume_path' => $resumePath,
'cv_path' => $cvPath,
'additional_notes' => $request->additional_notes,
'status' => 'pending',
]);

return response()->json([
'message' => 'Applied successfully',
'application' => $application,
], 201);
}







// Show single application with internship details
public function show(Request $request, $id)
{
$student = $request->user();

$application = Application::with('internship')
->where('student_id', $student->id)
->findOrFail($id);

return response()->json($application);
}

// Update application (e.g. update files or notes)
public function update(Request $request, $id)
{
$student = $request->user();

$application = Application::where('student_id', $student->id)->findOrFail($id);

$validator = Validator::make($request->all(), [
'resume' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
'cv' => 'sometimes|file|mimes:pdf,doc,docx|max:2048',
'additional_notes' => 'nullable|string',
]);

if ($validator->fails()) {
return response()->json($validator->errors(), 422);
}

// Replace files if provided
if ($request->hasFile('resume')) {
if ($application->resume_path) {
Storage::disk('public')->delete($application->resume_path);
}
$application->resume_path = $request->file('resume')->store('uploads/resumes', 'public');
}

if ($request->hasFile('cv')) {
if ($application->cv_path) {
Storage::disk('public')->delete($application->cv_path);
}
$application->cv_path = $request->file('cv')->store('uploads/cvs', 'public');
}

$application->additional_notes = $request->additional_notes ?? $application->additional_notes;

$application->save();

return response()->json([
'message' => 'Application updated successfully',
'application' => $application,
]);
}

// Delete application and associated files
public function destroy(Request $request, $id)
{
$student = $request->user();

$application = Application::where('student_id', $student->id)->findOrFail($id);

if ($application->resume_path) {
Storage::disk('public')->delete($application->resume_path);
}
if ($application->cv_path) {
Storage::disk('public')->delete($application->cv_path);
}

$application->delete();

return response()->json(['message' => 'Application deleted successfully']);
}
}
