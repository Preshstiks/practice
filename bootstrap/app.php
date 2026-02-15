<?php

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
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    // Force JSON for API routes
    $exceptions->shouldRenderJsonWhen(function ($request, $e) {
        return $request->is('api/*') || $request->expectsJson();
    });

    // Handle Unauthenticated
    $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated'
            
        ], 401);
    });
    $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated',
            'error' => $e->getMessage()
        ], 404);
    });

    // Handle Validation
    $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $e->getMessage()
        ], 422);
    });

    // Handle All Other Errors (500)
    $exceptions->render(function (\Throwable $e, $request) {

        if ($request->is('api/*')) {

            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);

        }

    });

})
->create();



