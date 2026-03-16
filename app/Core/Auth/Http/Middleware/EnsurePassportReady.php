<?php

namespace App\Core\Auth\Http\Middleware;

use Closure;
use Laravel\Passport\Passport;

class EnsurePassportReady
{
    public function handle($request, Closure $next)
    {
        if (!class_exists(Passport::class)) {
            abort(500, 'Passport is not installed.');
        }

        return $next($request);
    }
}
