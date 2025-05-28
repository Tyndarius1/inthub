<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdminAuthController;

Route::get('/qr/login/{token}', [AdminAuthController::class, 'qrLogin'])->name('qr.login');

Route::get('/', function () {
    return view('welcome');
});
