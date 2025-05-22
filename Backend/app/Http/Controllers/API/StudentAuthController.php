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


class StudentAuthController extends Controller
{


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
            'gender' => 'required',
            'phone' => ['required', 'regex:/^(09|\+639)\d{9}$/'], // phone validation for PH format
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







public function login(Request $request)
{
$student = Student::where('email', $request->email)->first();

if (!$student || !Hash::check($request->password, $student->password)) {
return response()->json(['message' => 'Invalid credentials'], 401);
}

 // 🔒 Enforce email verification
    if (!$student->is_verified) {
        return response()->json([
            'message' => 'Please verify your email using the OTP sent to you before logging in.'
        ], 403);
    }

$token = $student->createToken('student_token')->plainTextToken;

return response()->json([
'student' => $student,
'token' => $token,
]);
}

public function profile(Request $request)
{
return response()->json([
'student' => $request->user(),
]);
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







public function logout(Request $request)
{
$request->user()->currentAccessToken()->delete();
return response()->json([
'message' => 'Student logged out successfully',
]);
}






public function update(Request $request)
{
    $student = $request->user();

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'dob' => 'sometimes|date',
        'gender' => 'sometimes|string|in:male,female,other',
        'phone' => 'sometimes|string|max:20',
        'address' => 'sometimes|string|max:255',
        'school' => 'sometimes|string|max:255',
        'course' => 'sometimes|string|max:255',
        'year_level' => 'sometimes|string|max:20',
        'student_id_number' => 'sometimes|string|max:50',
        'student_pic' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,svg|max:2048', // Accept image file
        'password' => 'sometimes|string|min:6|confirmed',
    ]);

    $data = $request->except(['password', 'password_confirmation', 'student_pic']);

    // Handle password update
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // Handle student_pic upload
    if ($request->hasFile('student_pic')) {
        // Optional: Delete old picture file here if you want to clean storage
        
        $imgPath = $request->file('student_pic')->store('student_pic', 'public');
        $data['student_pic'] = $imgPath;
    }

    $student->update($data);

    // Append full URL for student_pic
    $student->student_pic = $student->student_pic ? asset('storage/' . $student->student_pic) : null;

    return response()->json([
        'message' => 'Student profile updated successfully',
        'student' => $student,
    ]);
}







public function destroy(Request $request)
{
$student = $request->user();

$student->tokens()->delete(); // remove all tokens first
$student->delete();

return response()->json([
'message' => 'Student account deleted successfully',
]);
}



}