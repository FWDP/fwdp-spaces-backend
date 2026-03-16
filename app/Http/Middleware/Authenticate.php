<?php

namespace app\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * For API requests, we NEVER redirect.
     * We return JSON instead.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return null;
        }

        return null;
    }
}
