<?php

namespace App\Core\Messaging\Contracts;

interface MessageBus
{
    public function dispatch(object $event): void;
}
