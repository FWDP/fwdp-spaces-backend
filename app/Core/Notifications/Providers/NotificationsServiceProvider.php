<?php

namespace App\Core\Notifications\Providers;

use App\Core\Notifications\Events\NotificationCreated;
use App\Core\Notifications\Listeners\DispatchNotificationListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(NotificationCreated::class, DispatchNotificationListener::class);
    }
}
