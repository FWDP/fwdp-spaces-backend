<?php

namespace App\Core\Notifications\Listeners;

use App\Core\Notifications\Events\NotificationCreated;
use App\Core\Notifications\Jobs\DispatchNotificationJob;

class DispatchNotificationListener
{
    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        DispatchNotificationJob::dispatch($event->notification);
    }
}
