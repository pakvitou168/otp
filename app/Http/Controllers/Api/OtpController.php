<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function __construct(
        private OtpService $otpService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $otp = $this->otpService->create($request->user());

        return response()->json([
            'message' => 'Verification code sent successfully',
            'expires_at' => $otp->expires_at
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|size:6']);

        $isValid = $this->otpService->verify(
            $request->user(),
            $request->code
        );

        if (!$isValid) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 422);
        }

        return response()->json([
            'message' => 'Verification successful'
        ]);
    }
}
