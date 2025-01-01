<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Otp extends Model
{
    protected $fillable = ['user_id', 'code', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', Carbon::now())
            ->where('used', false);
    }

    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }

    public function markAsUsed(): void
    {
        $this->used = true;
        $this->save();
    }
}
