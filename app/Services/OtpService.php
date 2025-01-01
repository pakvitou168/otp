<?php
namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    private const VALIDITY_MINUTES = 10;

    public function __construct(
        private OtpGenerator $generator,
        private OtpNotifier $notifier
    ) {}

    public function create(User $user): Otp
    {
        // Generate new OTP
        $code = $this->generator->generate();

        // Create OTP record
        $otp = Otp::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => Carbon::now()->addMinutes(self::VALIDITY_MINUTES),
        ]);

        // Send notification
        $this->notifier->sendEmail($user, $code, self::VALIDITY_MINUTES);

        return $otp;
    }

    public function verify(User $user, string $code): bool
    {
        $otp = Otp::where('user_id', $user->id)
            ->valid()
            ->latest()
            ->first();

        if (!$otp || !Hash::check($code, $otp->code)) {
            return false;
        }

        $otp->markAsUsed();
        return true;
    }
}
