<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Http\Middleware\HandleCors;
// use App\Http\Middleware\CustomCorsMiddleware; // Commented out - middleware doesn't exist

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',

        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Apply CORS middleware globally
        // $middleware->append(CustomCorsMiddleware::class); // Commented out - middleware doesn't exist
        $middleware->append(HandleCors::class);
        
        // Apply CORS to API routes specifically
        $middleware->api(append: [
            // CustomCorsMiddleware::class, // Commented out - middleware doesn't exist
            HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
