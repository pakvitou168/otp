<?php

namespace App\Services;

class OtpGenerator
{
    private const LENGTH = 6;

    public function generate(): string
    {
        return str_pad((string)random_int(0, pow(10, self::LENGTH) - 1), self::LENGTH, '0', STR_PAD_LEFT);
    }
}
