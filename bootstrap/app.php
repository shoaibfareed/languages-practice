<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',

        // ADD THIS
        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // ADD THIS
        $middleware->api(prepend: [
//            EnsureFrontendRequestsAreStateful::class,
        ]);

    })

    // ->withRateLimiting(function () {
    //     RateLimiter::for('api', function ($request) {
    //         return Limit::perMinute(120)->by($request->user()?->id ?? $request->ip());
    //     });
    // })

    ->withExceptions(function ($exceptions) {

        $exceptions->render(function (AuthenticationException $e, $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        });

    })

    ->create();
