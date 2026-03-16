<?php

namespace App\Core\Profile\Middleware;

use Illuminate\Http\Request;

class EnsureUserHasProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, \Closure $next): \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
    {
        $user = $request->user();

        if (!$user) {
            return \response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->profile()->exists()) {
            return \response()->json([
                'message' => 'Profile required. Please complete your profile.',
            ], 403);
        }

        return $next($request);
    }
}
