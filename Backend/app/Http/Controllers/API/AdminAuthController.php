<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Endroid\QrCode\QrCode; // Use Endroid QR code
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    // 1. Login API
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials or unauthorized.'], 401);
        }

        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    


     // Generate the QR Code for login
    public function generateQrToken(Request $request)
    {
        // Ensure the user is authenticated (only admins can generate QR tokens)
        $user = auth()->user();

        if ($user->role != 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Generate a unique token (could be a JWT or random token for simplicity)
        $qrToken = Str::random(60);

        // Save the token to the user model for future validation (you might want to store it)
        $user->update([
            'login_token' => $qrToken,
            'login_token_created_at' => now(),
        ]);

        // Generate the URL for login via QR (this should match the route on the backend)
        $qrCodeUrl = route('qr.login', ['token' => $qrToken]);

        // Create the QR code
        $qrCode = new QrCode($qrCodeUrl);  // Pass the URL as the data to the QR code
        $qrCode->setSize(300);  // Set the QR code size
        $qrCode->setMargin(10);  // Optional: add a margin around the QR code

        // Generate QR code image as PNG binary data
        $qrCodeData = $qrCode->writeString();

        // Convert to Base64 for embedding into HTML
        $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCodeData);

        return response()->json([
            'message' => 'QR token generated successfully',
            'qr_code' => $qrCodeDataUri  // Return the base64 QR code image
        ]);
    }

    // Handle login via QR code
    public function qrLogin(Request $request)
    {
        // Look for the user based on the QR token passed in the URL
        $user = User::where('login_token', $request->token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired QR token.'], 400);
        }

        // Check if the token has expired (for example, after 5 minutes)
        if (Carbon::parse($user->login_token_created_at)->addMinutes(5)->isPast()) {
            return response()->json(['message' => 'QR token has expired. Please generate a new one.'], 400);
        }

        // Log the user in (this assumes you are using Laravel's default authentication)
        auth()->login($user);

        // Invalidate the token after use
        $user->update(['login_token' => null]);

        // Generate an API token for the session
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'QR login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
