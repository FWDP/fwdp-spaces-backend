<?php

use App\Core\Auth\Http\Middleware\EnsurePassportReady;
use App\Core\Auth\Http\Middleware\EnsureRole;
use App\Core\Membership\Http\Middleware\EnsureUserHasPermission;
use App\Core\Subscriptions\Http\Middleware\EnsureActiveSubscription;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // AUTH
            'auth' => Authenticate::class,
            'permission' => EnsureUserHasPermission::class,
            'passport.ready' => EnsurePassportReady::class,

            // CUSTOM
            'role' => EnsureRole::class,
            'subscription.active' => EnsureActiveSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withEvents(
        discover: __DIR__.'/../app/Listeners',
    )
    ->create();
