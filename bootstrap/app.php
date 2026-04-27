<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Ensure guest carts get a session token (append to web group)
        $middleware->appendToGroup('web', App\Http\Middleware\EnsureCartSession::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
