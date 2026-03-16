<?php

namespace App\Core\Profile\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, \Closure $next): JsonResponse|Response
    {
        $user = $request->user();

        if (! $user) {
            return \response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (! $user->profile()->exists()) {
            return \response()->json([
                'message' => 'Profile required. Please complete your profile.',
            ], 403);
        }

        return $next($request);
    }
}
