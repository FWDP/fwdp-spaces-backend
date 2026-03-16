<?php

namespace App\Core\Subscriptions\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $subscription = $request->user()
            ->subscriptions()
            ->latest()
            ->first();

        if (! $subscription || ! $subscription->isActive()) {
            return response()->json(
                ['message' => 'Active subscription required'],
                Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
