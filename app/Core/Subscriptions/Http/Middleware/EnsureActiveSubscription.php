<?php

namespace App\Core\Subscriptions\Http\Middleware;

use App\Core\Subscriptions\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, Subscription $subscription): Response
    {
        $subscription = $request->user()
            ->subscriptions()
            ->latest()
            ->first();

        if (!$subscription || !$subscription->isActive()) return response([])->json(
                ['message' => 'Active subscription required'],
                Response::HTTP_FORBIDDEN
        );

        return $next($request);
    }
}
