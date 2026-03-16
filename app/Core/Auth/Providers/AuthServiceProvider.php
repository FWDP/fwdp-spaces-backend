<?php

namespace App\Core\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addHours(12));
        Passport::refreshTokensExpireIn(now()->addDays(30));

        Passport::tokensCan([
            'user' => 'Basic User',
            'admin' => 'Administrator',
        ]);
    }

    function register(): void
    {
        $this->loadRoutesFrom(base_path()."/app/Core/Auth/routes/api.php");
    }
}
