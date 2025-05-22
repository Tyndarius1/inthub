<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Mail\OtpMail;
use App\Models\Employer;
use App\Mail\SendOtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmployerAuthController extends Controller
{

public function showAllEmployer()
{
    $employers = Employer::all()->map(function ($employer) {
        $filePath = storage_path('app/public/' . $employer->company_pic);
        $employer->file_exists = file_exists($filePath);
        $employer->picture_url = $employer->company_pic 
            ? asset('storage/' . $employer->company_pic) 
            : null;
        return $employer;
    });

    return response()->json($employers);
}

    

public function register(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employers,email',
            'password' => 'required|min:6',
            'industry' => 'required|string',
            'description' => 'required|string',
            'website' => 'nullable|url',
            'phone' => ['required', 'regex:/^(09|\+639)\d{9}$/'],
            'location' => 'required|string',
            'contact_person' => 'required|string',
            'company_pic' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ], [
            'phone.regex' => 'The phone number must be a valid Philippine mobile number (e.g., 09171234567 or +639171234567).',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imgPath = null;
        if ($request->hasFile('company_pic')) {
            $imgPath = $request->file('company_pic')->store('employer_pic', 'public');
        }

        $otp = random_int(100000, 999999);
        $otpExpiresAt = now()->addMinutes(10);

        $employer = Employer::create([
            'company_name' => $request->company_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'industry' => $request->industry,
            'description' => $request->description,
            'website' => $request->website,
            'phone' => $request->phone,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'company_pic' => $imgPath,
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
            'is_verified' => false,
        ]);

        Mail::to($employer->email)->send(new OtpMail($otp));

        $employer->company_pic = $employer->company_pic ? asset('storage/' . $employer->company_pic) : null;
        $token = $employer->createToken('employer_token')->plainTextToken;

        return response()->json([
            'employer' => $employer,
            'token' => $token,
            'message' => 'Registration successful! OTP sent to your email.',
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Employer Registration Error: ' . $e->getMessage());

        return response()->json([
            'message' => 'An unexpected error occurred during registration. Please try again later.',
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}




public function resendOtp(Request $request)
{
    $employer = Employer::where('email', $request->email)
        ->where('is_verified', false)
        ->first();

    if (!$employer) {
        return response()->json(['message' => 'Account not found or already verified.'], 404);
    }

    $otp = random_int(100000, 999999);
    $employer->otp = $otp;
    $employer->otp_expires_at = now()->addMinutes(10);
    $employer->save();

    Mail::to($employer->email)->send(new OtpMail($otp));

    return response()->json(['message' => 'A new OTP has been sent to your email.']);
}







public function login(Request $request)
{
$employer = Employer::where('email', $request->email)->first();

if (!$employer || !Hash::check($request->password, $employer->password)) {
return response()->json(['message' => 'Invalid credentials'], 401);
}

// 🔒 Enforce email verification
    if (!$employer->is_verified) {
        return response()->json([
            'message' => 'Please verify your email using the OTP sent to you before logging in.'
        ], 403);
    }


$token = $employer->createToken('employer_token')->plainTextToken;

return response()->json([
'employer' => $employer,
'token' => $token,
]);
}





public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|string',
    ]);

    $employer = Employer::where('email', $request->email)->first();

    if (!$employer) {
        return response()->json(['message' => 'Employer not found.'], 404);
    }

    if ($employer->is_verified) {
        return response()->json(['message' => 'Employer already verified.'], 200);
    }

    if ($employer->otp !== $request->otp) {
        return response()->json(['message' => 'Invalid OTP.'], 400);
    }

    if (now()->greaterThan($employer->otp_expires_at)) {
        return response()->json(['message' => 'OTP has expired.'], 400);
    }

    $employer->is_verified = true;
    $employer->otp = null;
    $employer->otp_expires_at = null;
    $employer->save();

    return response()->json(['message' => 'Email verified successfully. You may now log in.'], 200);
}







public function profile(Request $request)
{
return response()->json([
'employer' => $request->user(),
]);
}



public function logout(Request $request)
{
$request->user()->currentAccessToken()->delete();
return response()->json([
'message' => 'Employer logged out successfully',
]);
}




public function update(Request $request)
{
try {
$employer = $request->user();

$request->validate([
    'company_name' => 'sometimes|string|max:255',
    'email' => 'sometimes|email|unique:employers,email,' . $employer->id,
    'industry' => 'sometimes|string|max:255',
    'description' => 'sometimes|string',
    'website' => 'sometimes|nullable|url',
    'phone' => 'sometimes|string|max:20',
    'location' => 'sometimes|string|max:255',
    'contact_person' => 'sometimes|string|max:255',
    'company_pic' => 'sometimes|nullable|image|mimes:jpg,jpeg,png,svg|max:2048', // Accept image upload
    'password' => 'sometimes|string|min:6|confirmed',
]);

$data = $request->except(['password', 'password_confirmation', 'company_pic']);

if ($request->filled('password')) {
    $data['password'] = Hash::make($request->password);
}

// Handle image upload
if ($request->hasFile('company_pic')) {
    // Optional: Delete old picture file from storage
    if ($employer->company_pic && \Storage::disk('public')->exists($employer->company_pic)) {
        \Storage::disk('public')->delete($employer->company_pic);
    }

    $imgPath = $request->file('company_pic')->store('company_pic', 'public');
    $data['company_pic'] = $imgPath;
}

// Filter out unchanged values
$changedData = collect($data)->filter(function ($value, $key) use ($employer) {
    return $value != $employer->$key;
});

if ($changedData->isEmpty()) {
    return response()->json([
        'message' => 'No changes detected. Please update at least one field.',
    ], 422);
}

$employer->update($changedData->toArray());

// Append full URL to company_pic for response
$employer->company_pic = $employer->company_pic ? asset('storage/' . $employer->company_pic) : null;

return response()->json([
    'message' => 'Employer profile updated successfully',
    'employer' => $employer,
]);

} catch (\Exception $e) {
\Log::error('Employer Update Error: ' . $e->getMessage());
return response()->json([
    'message' => 'An unexpected error occurred while updating. Please try again later.',
], 500);
}
}






public function destroy(Request $request)
{
try {
$employer = $request->user();

$employer->tokens()->delete(); // remove all access tokens
$employer->delete();

return response()->json([
    'message' => 'Employer account deleted successfully',
]);

} catch (\Exception $e) {
\Log::error('Employer Deletion Error: ' . $e->getMessage());
return response()->json([
    'message' => 'An unexpected error occurred while deleting the account.',
], 500);
}
}



}
