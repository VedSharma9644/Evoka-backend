<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\CustomCorsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Apply CORS middleware globally
        $middleware->append(CustomCorsMiddleware::class);
        $middleware->append(HandleCors::class);
        
        // Apply CORS to API routes specifically
        $middleware->api(append: [
            CustomCorsMiddleware::class,
            HandleCors::class,
        ]);

     
    //       $middleware->validateCsrfTokens(except: [
    //     '*'
    // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
