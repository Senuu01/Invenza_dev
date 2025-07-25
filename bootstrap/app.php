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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 404 errors
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Route not found.',
                    'error' => 'The requested resource could not be found.',
                    'code' => 404
                ], 404);
            }

            return response()->view('errors.404', [
                'message' => 'The page you are looking for could not be found.',
                'suggestion' => 'Please check the URL or return to the homepage.'
            ], 404);
        });

        // Handle 403 errors (Access Denied)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied.',
                    'error' => $e->getMessage(),
                    'code' => 403
                ], 403);
            }

            return response()->view('errors.403', [
                'message' => $e->getMessage() ?: 'You do not have permission to access this page.',
                'suggestion' => 'Please contact an administrator if you believe this is an error.'
            ], 403);
        });

        // Handle 500 errors (Server Errors)
        $exceptions->render(function (\Throwable $e, $request) {
            if (app()->environment('production')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Internal server error.',
                        'error' => 'Something went wrong on our end.',
                        'code' => 500
                    ], 500);
                }

                return response()->view('errors.500', [
                    'message' => 'Something went wrong on our end.',
                    'suggestion' => 'Please try again later or contact support if the problem persists.'
                ], 500);
            }
        });

        // Handle model not found exceptions
        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found.',
                    'error' => 'The requested resource does not exist.',
                    'code' => 404
                ], 404);
            }

            return redirect()->back()->withErrors(['error' => 'The requested resource was not found.']);
        });
    })->create();
