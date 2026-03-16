<?php

namespace App\Core\Notifications\Providers;

use App\Core\Notifications\Events\NotificationCreated;
use App\Core\Notifications\Listeners\DispatchNotificationListener;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    protected $listen = [
        NotificationCreated::class => [
            DispatchNotificationListener::class,
        ]
    ];
}
