<?php

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// routes/api.php



Route::get('/ping', function () {
    return response()->json(['success' => true]);
});
// Public routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    // Email verification routes
    Route::post('/email/verification-notification',
        [EmailVerificationController::class, 'sendVerificationEmail'])
        ->name('verification.send');

    Route::post('/email/verify',
        [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');

    // Routes that require verified email
    Route::middleware('verified')->group(function () {
        // Your protected routes here
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
    Route::post('/otp/generate', [\App\Http\Controllers\Api\Auth\OtpController::class, 'generate']);
    Route::post('/otp/verify', [\App\Http\Controllers\Api\Auth\OtpController::class, 'verify']);
});
