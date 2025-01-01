<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OtpController extends BaseApiController
{
    public function __construct(
        private OtpService $otpService
    ) {}

    public function generate(Request $request): JsonResponse
    {
        $otp = $this->otpService->create($request->user());

        return response()->json([
            'message' => 'Verification code sent successfully',
            'expires_at' => $otp->expires_at,
            'expires_at_local' => $otp->expires_at->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s'), // Cambodia time
            'timezone' => 'Asia/Phnom_Penh'
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'code' => 'required|string|size:6'
            ]);

            // Check if user exists
            $user = $request->user();
            if (!$user) {
                return $this->errorResponse('Unauthenticated user', [], 401);
            }

            // Verify OTP
            $isValid = $this->otpService->verify($user, $validated['code']);

            if (!$isValid) {
                return $this->errorResponse('Invalid verification code', [], 422);
            }

            return $this->successResponse([
                'message' => 'Verification successful'
            ]);

        } catch (ValidationException $e) {
            return $this->errorResponse(
                'Validation failed',
                $e->errors(),
                422
            );
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('OTP verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id
            ]);

            return $this->errorResponse(
                'Failed to verify code',
                [],
                500
            );
        }
    }
}
