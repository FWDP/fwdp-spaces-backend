<?php

namespace App\Core\Messaging\Services;

use App\Core\Messaging\Contracts\MessageBus;
use Illuminate\Support\Facades\Event;

class EventBus implements MessageBus
{
    public function dispatch(object $event): void
    {
        Event::dispatch($event);
    }
}
