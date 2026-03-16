<?php

namespace App\Core\Membership\Policies;

use App\Core\Membership\Authorisation\PermissionMap;
use App\Core\Membership\Enums\Permission;
use App\Models\User;

class BasePolicy
{
    protected function check(User $user, string $ability): bool
    {
        $permission = PermissionMap::permissionFor($ability);

        if (!$permission) return false;

        return $user->hasPermission(Permission::from($permission));
    }
}
