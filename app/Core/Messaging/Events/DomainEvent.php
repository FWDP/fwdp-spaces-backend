<?php

namespace App\Core\Messaging\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DomainEvent
{
    public string $eventId;

    public \DateTime $occurAt;

    public function __construct()
    {
        $this->eventId = (string)\Str::uuid();
        $this->occurAt = new \DateTime();
    }

}
