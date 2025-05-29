<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminAuthController;
use App\Http\Controllers\API\StudentAuthController;
use App\Http\Controllers\API\EmployerAuthController;
use App\Http\Controllers\EmployerApplicationController;
use App\Http\Controllers\API\EmployerInternshipController;
use App\Http\Controllers\API\StudentApplicationController;



Route::prefix('admin')->group(function () {
Route::post('/login', [AdminAuthController::class, 'login']);
Route::get('/qr/login/{token}', [AdminAuthController::class, 'qrLogin'])->name('qr.login');

});







// Public Routes For Student
Route::prefix('student')->group(function () {
Route::get('/list', [StudentAuthController::class, 'showAllStudent']);
Route::post('/register', [StudentAuthController::class, 'register']);
Route::post('/login', [StudentAuthController::class, 'login']);
Route::post('/verify-otp', [StudentAuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [StudentAuthController::class, 'resendOtp']);
// Student forgot/reset password
Route::post('/forgot-password', [StudentAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [StudentAuthController::class, 'resetPassword']);

});

// Public Routes For Employer
Route::prefix('employer')->group(function () {
Route::get('/list', [EmployerAuthController::class, 'showAllEmployer']);
Route::post('/register', [EmployerAuthController::class, 'register']);
Route::post('/login', [EmployerAuthController::class, 'login']);
Route::post('/verify-otp', [EmployerAuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [EmployerAuthController::class, 'resendOtp']);
// Student forgot/reset password
Route::post('/forgot-password', [EmployerAuthController::class, 'forgotPassword']);
Route::post('/reset-password', [EmployerAuthController::class, 'resetPassword']);

});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

// Student Profile Route
Route::prefix('student')->group(function () {
Route::get('/export', [StudentAuthController::class, 'export']);
Route::get('/profile/{id}', [StudentAuthController::class, 'profile']);
Route::post('/update/{id}', [StudentAuthController::class, 'update']);
Route::delete('/delete/{id}', [StudentAuthController::class, 'destroy']);
Route::post('/logout', [StudentAuthController::class, 'logout']);

// Student Internship Application routes
Route::apiResource('applications', StudentApplicationController::class);
});

// Employer Protected Routes
Route::prefix('employer')->group(function () {
Route::get('/profile/{id}', [EmployerAuthController::class, 'profile']);
Route::post('/update/{id}', [EmployerAuthController::class, 'update']);
Route::delete('/delete/{id}', [EmployerAuthController::class, 'destroy']);
Route::post('/logout', [EmployerAuthController::class, 'logout']);

// Employer Internship CRUD routes
Route::apiResource('internships', EmployerInternshipController::class);

// Employer Accept Or Reject Application
Route::post('/applications/{applicationId}/status', [EmployerInternshipController::class, 'updateApplicationStatus']);
});

Route::prefix('admin')->group(function () {
   Route::post('/generate-qr-token', [AdminAuthController::class, 'generateQrToken']);
});
});
