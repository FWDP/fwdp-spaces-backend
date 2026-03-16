<?php

use App\Core\Profile\Http\Middleware\EnsureUserHasProfile;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases (THIS IS THE IMPORTANT PART)
        |--------------------------------------------------------------------------
        */
        $middleware->alias([
            // AUTH
            'auth' => \App\Http\Middleware\Authenticate::class,
            'passport.ready' => \App\Core\Auth\Http\Middleware\EnsurePassportReady::class,

            // CUSTOM
            'role' => \App\Core\Auth\Http\Middleware\EnsureRole::class,
            'subscription.active' => \App\Core\Subscriptions\Http\Middleware\EnsureActiveSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
