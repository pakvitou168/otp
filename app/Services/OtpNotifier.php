<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OtpNotifier
{
    public function sendEmail(User $user, string $otp, int $validityMinutes): void
    {
        Mail::send('emails.otp', [
            'otp' => $otp,
            'validityMinutes' => $validityMinutes
        ], function($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Verification Codes');
        });
    }
}
