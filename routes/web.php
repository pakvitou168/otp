<?php

use App\Http\Controllers\Api\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/otp/generate', [OtpController::class, 'generate']);
Route::post('/otp/verify', [OtpController::class, 'verify']);
