<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

        // Check if the user already has an existing QR token
        if ($user->login_token) {
            // Optionally, allow user to delete the existing token and generate a new one
            if ($request->has('regenerate') && $request->regenerate === 'true') {
                // Delete the existing token and proceed to generate a new one
                $user->update([
                    'login_token' => null,
                    'login_token_created_at' => null,  // Optionally clear the created_at field
                ]);
            } else {
                return response()->json(['message' => 'A QR token already exists. Please use it or generate a new one.'], 400);
            }
        }

        // Generate a new unique token (random string)
        $qrToken = Str::random(60);

        // Save the token to the user model for future validation
        $user->update([
            'login_token' => $qrToken,
            'login_token_created_at' => now(), // Optionally store the creation time
        ]);

        // Generate the URL for login via QR (this should match the route on the backend)
        $qrCodeUrl = route('qr.login', ['token' => $qrToken]);

        // Generate QR code using SimpleQR
        $qrCode = QrCode::format('png')->size(300)->generate($qrCodeUrl);

        // Convert to Base64 for embedding into HTML
        $qrCodeDataUri = 'data:image/png;base64,' . base64_encode($qrCode);

        return response()->json([
            'message' => 'QR token generated successfully',
            'qr_code' => $qrCodeDataUri  // Return the base64 QR code image
        ]);
    }

   






   public function qrLogin($token)
{
    $user = User::where('login_token', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid QR token. Please generate a new one.'], 400);
    }

    

    // Log the user in
    auth()->login($user);



    // Create a new API token
    $token = $user->createToken('admin-token')->plainTextToken;

    return response()->json([
        'message' => 'QR login successful',
        'token' => $token,
        'user' => $user,
    ]);
}



}
