<?php

namespace App\Core\Subscriptions\Providers;

use Illuminate\Support\ServiceProvider;

class SubscriptionsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

    }

    public function register(): void
    {
        $this->loadRoutesFrom(base_path()."/app/Core/Subscriptions/routes/api.php");
        $this->loadMigrationsFrom(base_path()."/app/Core/Subscriptions/database/migrations");
    }
}
