<?php

namespace App\Core\Auth\Services;

use App\Models\User;
use Http;
use Illuminate\Http\Client\ConnectionException;
use Laravel\Passport\Client;
use Laravel\Passport\Token;

class PassportService
{
    public function createPersonalAccessToken(User $user, string $name='auth_token'): string
    {
        return $user->createToken($name)->accessToken;
    }

    public function revokeAllTokens(User $user, Token $token): void
    {
        $token->where('user_id', $user->id)->delete();
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->token()->revoke();
    }

    public function createPasswordGrantToken(User $user, string $name='password_grant'): string
    {
        return $user->createToken($name)->accessToken;
    }
}
