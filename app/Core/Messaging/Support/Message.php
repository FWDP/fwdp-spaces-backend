<?php

namespace App\Core\Messaging\Support;

class Message
{
    public object $event;

    public function __construct(object $event)
    {
        $this->event = $event;
    }
}