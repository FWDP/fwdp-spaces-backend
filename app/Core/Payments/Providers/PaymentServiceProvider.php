<?php

namespace App\Core\Payments\Providers;

use App\Core\Payments\Contracts\PaymentGateway;
use App\Core\Payments\Gateways\TestGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            PaymentGateway::class,
            TestGateway::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
