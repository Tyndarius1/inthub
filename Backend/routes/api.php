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
});





// Public Routes For Student
Route::prefix('student')->group(function () {
Route::get('/list', [StudentAuthController::class, 'showAllStudent']);
Route::post('/register', [StudentAuthController::class, 'register']);
Route::post('/login', [StudentAuthController::class, 'login']);
Route::post('/verify-otp', [StudentAuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [StudentAuthController::class, 'resendOtp']);
});

// Public Routes For Employer
Route::prefix('employer')->group(function () {
Route::get('/list', [EmployerAuthController::class, 'showAllEmployer']);
Route::post('/register', [EmployerAuthController::class, 'register']);
Route::post('/login', [EmployerAuthController::class, 'login']);
Route::post('/verify-otp', [EmployerAuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [EmployerAuthController::class, 'resendOtp']);
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

// Student Profile Route
Route::prefix('student')->group(function () {
Route::get('/profile', [StudentAuthController::class, 'profile']);
Route::match(['put', 'post'], '/update', [StudentAuthController::class, 'update']);
Route::delete('/delete', [StudentAuthController::class, 'destroy']);
Route::post('/logout', [StudentAuthController::class, 'logout']);

// Student Internship Application routes
Route::apiResource('applications', StudentApplicationController::class);
});

// Employer Profile Routes
Route::prefix('employer')->group(function () {
Route::get('/profile', [EmployerAuthController::class, 'profile']);
Route::match(['put', 'post'], '/update', [EmployerAuthController::class, 'update']);
Route::delete('/delete', [EmployerAuthController::class, 'destroy']);
Route::post('/logout', [EmployerAuthController::class, 'logout']);

// Employer Internship CRUD routes
Route::apiResource('internships', EmployerInternshipController::class);

// Employer Accept Or Reject Application
Route::match(['put', 'post'], '/applications/{applicationId}/status', [EmployerInternshipController::class, 'updateApplicationStatus']);
});

});
