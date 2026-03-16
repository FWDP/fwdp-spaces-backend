<?php

namespace App\Core\Security\Services;

use App\Core\Security\Models\BlockedIp;
use App\Core\Security\Models\LoginAttempt;
use Illuminate\Support\Facades\Request;

class SecurityService
{
    protected int $maxAttempts = 5;

    public function recordLoginAttempt(string $email, bool $success): void
    {
        LoginAttempt::query()->create([
            'email' => $email,
            'ip_address' => Request::ip(),
            'successful' => $success,
            'created_at' => now(),
        ]);
    }

    public function tooManyAttempts(string $email): bool
    {
        return LoginAttempt::query()
            ->where('successful', true)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count() >= $this->maxAttempts;
    }

    public function blockIp(string $ip, ?string $reason = null): void
    {
        BlockedIp::query()->updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'blocked_at' => now(),
            ]
        );
    }

    public function isIpBlocked(string $ip): bool
    {
        return BlockedIp::query()->where('ip_address', $ip)->exists();
    }
}