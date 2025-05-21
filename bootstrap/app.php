<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        // Register the 'admin' middleware alias
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // Other middleware configurations...
        $middleware->web(append: [
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
        ]);
    })
    ->withExceptions(function ($exceptions) {
        //
    })->create();