<?php

namespace App\Core\Messaging\Providers;

use App\Core\Messaging\Contracts\MessageBus;
use App\Core\Messaging\Services\EventBus;
use Illuminate\Support\ServiceProvider;

class MessagingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            MessageBus::class,
            EventBus::class
        );
    }
}
