<?php

namespace App\Core\Membership\Contracts;

interface Authenticable
{
    public function getAuthIdentifier();

    public function getAuthPassword();
}
