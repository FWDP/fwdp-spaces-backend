<?php

namespace App\Core\Membership\Traits;

use App\Core\Membership\Enums\Permission;
use App\Core\Membership\Enums\UserRole;
use App\Core\Membership\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

/*
 * @mixin Model
 * */
trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id'
        );
    }

    public function hasRole(UserRole $userRole): bool
    {
        return $this->roles()
            ->where('slug', $userRole->value)
            ->exists();
    }

    public function assignRole(UserRole $userRole): void
    {
        $roleModel = Role::query()
            ->where('slug', $userRole->value)
            ->first();

        if (! $roleModel) {
            return;
        }

        $this->roles()->syncWithoutDetaching([$roleModel->id]);
    }

    public function removeRole(UserRole $userRole): void
    {
        $roleModel = Role::query()
            ->where('slug', $userRole->value)
            ->first();

        if (! $roleModel) {
            return;
        }

        $this->roles()->detach([$roleModel->id]);

        Cache::forget("user_permissions_{$this->id}");
    }

    public function hasPermission(Permission $permission): bool
    {
        $cacheKey = "user_permissions_{$this->id}";

        $permissions = Cache::remember($cacheKey, 3600, function () {
            return $this->roles()
                ->with('permissions')
                ->get()
                ->flatMap(function ($role) {
                    return $role->permissions->pluck('slug');
                })
                ->unique()
                ->toArray();
        });

        return in_array($permission->value, $permissions);
    }
}
