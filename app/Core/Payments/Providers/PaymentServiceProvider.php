<?php

namespace App\Core\Payments\Providers;

use App\Core\Payments\Contracts\PaymentGateway;
use App\Core\Payments\Gateways\PayMongoGateway;
use App\Core\Payments\Gateways\TestGateway;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGateway::class, function () {
            return match (config('payments.gateway', 'test')) {
                'paymongo' => new PayMongoGateway,
                default => new TestGateway,
            };
        });
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/payments.php', 'payments');
    }
}
