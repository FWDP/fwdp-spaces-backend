<?php

namespace App\Core\Membership\Http\Controllers;

use App\Core\Membership\Models\Permission;
use App\Core\Membership\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        return Role::with('permissions')->get();
    }

    public function store(Request $request)
    {
       return response()->json(
           Role::query()->create([
               'name' => $request->name,
               'slug' => $request->slug,
           ]),
           Response::HTTP_CREATED
       );
    }

    public function show(Role $role)
    {
        return $role->load('permissions');
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());

        return response()->json('Role updated', Response::HTTP_OK);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json('Role deleted', Response::HTTP_OK);
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $permissions = Permission::query()
            ->whereIn('slug', $request->permissions)
            ->pluck('id');

        $role->permissions()
            ->sync($permissions);

        return $role->load('permissions');
    }
}
