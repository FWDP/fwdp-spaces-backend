<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Core\Membership\Contracts\HasRole;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request,
                           Closure $next,
                           string $roles): Response
    {
        if (!$request->user()->hasRole($roles)) {
            return response()->json([
                'message' => 'Forbidden',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
