<?php

namespace App\Core\Membership\Contracts;

interface HasRole
{
    public function getRole(): string;

    public function hasRole(string $role): bool;
}
