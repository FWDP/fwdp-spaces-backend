<?php

namespace App\Core\Membership\Policies;

use App\Core\Membership\Enums\Permission;
use App\Models\User;

class MembershipPolicy
{
    public function manageUsers(User $user): bool
    {
        return $user->hasPermission(Permission::MANAGE_USERS);
    }

    public function manageRoles(User $user): bool
    {
        return $user->hasPermission(Permission::MANAGE_ROLES);
    }
}
