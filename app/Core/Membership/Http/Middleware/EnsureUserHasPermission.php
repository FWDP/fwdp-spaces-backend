<?php

namespace App\Core\Membership\Http\Middleware;

use App\Core\Membership\Enums\Permission;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        string $permission
    ): Response
    {
        $user = $request->user();

        if (!$user) abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized');

        $permissionEnum = Permission::from(strtolower($permission));

        if (!$user->hasPermission($permissionEnum)) abort(Response::HTTP_FORBIDDEN, 'Forbidden');

        return $next($request);
    }
}
