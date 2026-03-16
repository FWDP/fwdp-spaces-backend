<?php

namespace App\Core\Membership\Http\Controllers;

use App\Core\Membership\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function assign(Request $request, User $user)
    {
        $roles = Role::query()
            ->whereIn('slug', $request->roles)
            ->pluck('id');

        $user->roles()->syncWithoutDetaching($roles);

        return $user->load('roles');
    }

    public function remove(Request $request, User $user)
    {
        $roles = Role::query()
            ->whereIn('slug', $request->roles)
            ->pluck('id');

        $user->roles()->detach($roles);

        return $user->load('roles');
    }
}
