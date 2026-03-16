<?php

namespace App\Core\Membership\Services;

use App\Core\Membership\Enums\Permission;
use App\Core\Membership\Enums\UserRole;
use App\Models\User;

class MembershipService
{
    public function assignRole(User $user, UserRole $userRole): void
    {
        $user->assignRole($userRole);
    }

    public function removeRole(User $user, UserRole $userRole): void
    {
        $user->removeRole($userRole);
    }

    public function hasRole(User $user, UserRole $userRole): bool
    {
        return $user->hasRole($userRole);
    }

    public function hasPermission(User $user, Permission $permission): bool
    {
        return $user->hasPermission($permission);
    }
}
