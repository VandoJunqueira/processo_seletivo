<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/api/login');


        $middleware->api([\App\Http\Middleware\CorsMiddleware::class]);

        $middleware->statefulApi([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'auth.refresh' => \App\Http\Middleware\TokenExpirationMiddleware::class,
        ]);

        $middleware->appendToGroup('api', \App\Http\Middleware\HandleApiTokenErrors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
