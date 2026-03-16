<?php

namespace App\Core\Membership\Contracts;

use App\Core\Membership\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasRole
{
    public function roles(): BelongsToMany;

    public function hasRole(UserRole $role): bool;

    public function assignRole(UserRole $role): bool;

    public function removeRole(UserRole $role): bool;
}
