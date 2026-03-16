<?php

namespace App\Core\Messaging\Events;

class DomainEvent
{
    public string $eventId;

    public \DateTime $occurAt;

    public function __construct()
    {
        $this->eventId = (string) \Str::uuid();
        $this->occurAt = new \DateTime;
    }
}
