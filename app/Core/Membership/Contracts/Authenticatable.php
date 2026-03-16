<?php

namespace App\Core\Membership\Contracts;

interface Authenticatable
{
    public function getAuthIdentifier();

    public function getAuthPassword();
}
