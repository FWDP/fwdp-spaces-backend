<?php

namespace App\Core\Auth\Services;

use App\Events\UserRegistered;
use App\Models\User;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Hash::make($data['password']),
        ]);

        event(new UserRegistered($user));
    }

    public function attemptLogin(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if(!$user) return null;

        if(!\Hash::check($password, $user->password)) return null;

        return $user;
    }
}