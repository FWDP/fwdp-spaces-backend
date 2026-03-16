<?php

namespace App\Core\Payments\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

    }

    public function register() : void
    {
        $this->loadMigrationsFrom(base_path()."/app/Core/Payments/Database/Migrations");
        $this->loadRoutesFrom(base_path()."/app/Core/Payments/Routes/api.php");
    }
}
