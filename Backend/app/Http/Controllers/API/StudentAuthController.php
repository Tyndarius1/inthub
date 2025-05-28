<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Mail\OtpMail;
use App\Models\Student;
use App\Mail\SendOtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Mail\StudentResetPasswordMail;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;



class StudentAuthController extends Controller
{


public function export()
{
    return Excel::download(new StudentsExport, 'students.csv');
}




public function showAllStudent()
{
$students = Student::all()->map(function ($student) {
$filePath = storage_path('app/public/' . $student->student_pic);
$student->file_exists = file_exists($filePath);
$student->picture_url = $student->student_pic 
? asset('storage/' . $student->student_pic) 
: null;
return $student;
});

return response()->json($students);
}





public function register(Request $request)
{
try {
$validator = Validator::make($request->all(), [
'name' => 'required',
'email' => 'required|email|unique:students,email',
'password' => 'required|min:6',
'dob' => 'required|date',
'gender' => 'required|in:Male,Female,Other',
'phone' => ['required', 'regex:/^(09|\+639)\d{9}$/'], 
'address' => 'required',
'school' => 'required',
'course' => 'required',
'year_level' => 'required',
'student_id_number' => 'required',
'student_pic' => 'nullable|image|mimes:jpg,png,svg,jpeg|max:2048',
]);

if ($validator->fails()) {
return response()->json(['errors' => $validator->errors()], 422);
}

// Format phone number to E.164 standard (+63...)
$phone = $request->phone;
if (preg_match('/^0/', $phone)) {
$phone = preg_replace('/^0/', '+63', $phone);
}

$imgPath = null;
if ($request->hasFile('student_pic')) {
$imgPath = $request->file('student_pic')->store('student_pic', 'public');
}

$otp = random_int(100000, 999999);
$otpExpiresAt = now()->addMinutes(10);

$student = Student::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
'dob' => $request->dob,
'gender' => $request->gender,
'phone' => $phone,
'address' => $request->address,
'school' => $request->school,
'course' => $request->course,
'year_level' => $request->year_level,
'student_id_number' => $request->student_id_number,
'student_pic' => $imgPath,
'otp' => $otp,
'otp_expires_at' => $otpExpiresAt,
'is_verified' => false,
]);

Mail::to($student->email)->send(new OtpMail($otp));

$student->student_pic = $student->student_pic ? asset('storage/' . $student->student_pic) : null;

$token = $student->createToken('student_token')->plainTextToken;

return response()->json([
'student' => $student,
'token' => $token,
'message' => 'Registration successful! OTP sent to your email.',
], 201);

} catch (\Exception $e) {
\Log::error('Student Registration Error: ' . $e->getMessage());

return response()->json([
'message' => 'An unexpected error occurred during registration. Please try again later.',
'trace' => $e->getTraceAsString()
], 500);
}
}





public function resendOtp(Request $request)
{
$student = Student::where('email', $request->email)
->where('is_verified', false)
->first();

if (!$student) {
return response()->json(['message' => 'Account not found or already verified.'], 404);
}

$otp = random_int(100000, 999999);
$student->otp = $otp;
$student->otp_expires_at = now()->addMinutes(10);
$student->save();

Mail::to($student->email)->send(new OtpMail($otp));

return response()->json(['message' => 'A new OTP has been sent to your email.']);
}












public function verifyOtp(Request $request)
{
$request->validate([
'email' => 'required|email',
'otp' => 'required|string',
]);

$student = Student::where('email', $request->email)->first();

if (!$student) {
return response()->json(['message' => 'Student not found.'], 404);
}

if ($student->is_verified) {
return response()->json(['message' => 'Student already verified.'], 200);
}

if ($student->otp !== $request->otp) {
return response()->json(['message' => 'Invalid OTP.'], 400);
}

if (now()->greaterThan($student->otp_expires_at)) {
return response()->json(['message' => 'OTP has expired.'], 400);
}

$student->is_verified = true;
$student->otp = null;
$student->otp_expires_at = null;
$student->save();

return response()->json(['message' => 'Email verified successfully. You may now log in.'], 200);
}






public function forgotPassword(Request $request)
{
$request->validate(['email' => 'required|email']);

$student = Student::where('email', $request->email)->first();

if (!$student) {
return response()->json(['message' => 'Email not found.'], 404);
}

$token = Str::random(60);
$student->reset_token = $token;
$student->reset_token_expires_at = now()->addMinutes(30);
$student->save();

Mail::to($student->email)->send(new StudentResetPasswordMail($student, $token));

return response()->json(['message' => 'Reset token sent to your email.']);
}



public function resetPassword(Request $request)
{
$request->validate([
'email' => 'required|email',
'token' => 'required|string',
'password' => 'required|string|confirmed|min:8',
]);

$student = Student::where('email', $request->email)
->where('reset_token', $request->token)
->where('reset_token_expires_at', '>', now())
->first();

if (!$student) {
return response()->json(['message' => 'Invalid or expired token.'], 400);
}

$student->password = Hash::make($request->password);
$student->reset_token = null;
$student->reset_token_expires_at = null;
$student->save();

return response()->json(['message' => 'Password reset successful.']);
}







public function login(Request $request)
{
$student = Student::where('email', $request->email)->first();

if (!$student || !Hash::check($request->password, $student->password)) {
return response()->json(['message' => 'Invalid credentials'], 401);
}

if (!$student->is_verified) {
return response()->json([
'message' => 'Please verify your email using the OTP sent to you before logging in.'
], 403);
}

$token = $student->createToken('student_token')->plainTextToken;

// 👇 Convert student_pic to full asset URL
$student->student_pic = $student->student_pic 
? asset('storage/' . $student->student_pic) 
: null;

return response()->json([
'student' => $student,
'token' => $token,
]);
}

public function profile(Request $request, $id)
{
    $student = Student::findOrFail($id);
    $student->student_pic = $student->student_pic ? asset('storage/' . $student->student_pic) : null;
    return response()->json($student);
}



public function logout(Request $request)
{
$request->user()->currentAccessToken()->delete();
return response()->json([
'message' => 'Student logged out successfully',
]);
}







public function update(Request $request, $id)
{
    try {
        $student = Student::findOrFail($id);

        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'dob' => 'sometimes|date',
            'gender' => 'sometimes|string|in:Male,Female,Other',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
            'school' => 'sometimes|string|max:255',
            'course' => 'sometimes|string|max:255',
            'year_level' => 'sometimes|string|max:20',
            'student_id_number' => 'sometimes|string|max:50',
            'student_pic' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'password' => 'nullable|string|min:6',
        ]);

        // Remove fields we’ll handle separately
        $data = collect($validated)->except(['password', 'student_pic'])->toArray();

        // Handle password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle image upload
        if ($request->hasFile('student_pic')) {
            // Delete old image if exists
            if ($student->student_pic && Storage::disk('public')->exists($student->student_pic)) {
                Storage::disk('public')->delete($student->student_pic);
            }

            $path = $request->file('student_pic')->store('student_pic', 'public');
            $data['student_pic'] = $path;
        }

        // Update student
        $updated = $student->update($data);

        // Re-fetch full updated model
        $student->refresh();

        return response()->json([
            'message' => $updated ? 'Student profile updated successfully' : 'No changes were made.',
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'dob' => $student->dob,
                'gender' => $student->gender,
                'phone' => $student->phone,
                'address' => $student->address,
                'school' => $student->school,
                'course' => $student->course,
                'year_level' => $student->year_level,
                'student_id_number' => $student->student_id_number,
                'student_pic' => $student->student_pic ? asset('storage/' . $student->student_pic) : null,
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Student Update Error: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json([
            'message' => 'An unexpected error occurred while updating.',
            'error' => $e->getMessage()
        ], 500);
    }
}







public function destroy($id)
{
try {
$student = Student::findOrFail($id);

$student->tokens()->delete();
$student->delete();

return response()->json([
'message' => 'Student account deleted successfully',
]);
} catch (\Exception $e) {
\Log::error('Student Deletion Error: ' . $e->getMessage());
return response()->json([
'message' => 'An unexpected error occurred while deleting the account.',
], 500);
}
}

}