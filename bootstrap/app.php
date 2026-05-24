<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // SSLCommerz callback routes — no session middleware so the redirect
            // response does NOT send a Set-Cookie header that would overwrite the
            // user's real session cookie and log them out.
            \Illuminate\Support\Facades\Route::middleware([
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ])->group(base_path('routes/payment_callbacks.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Stale Livewire temp file — redirect back with a flash error instead of crashing.
        $exceptions->render(function (\RuntimeException $e, \Illuminate\Http\Request $request) {
            if (str_starts_with($e->getMessage(), 'Unable to retrieve the file_size for file at location:')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'The uploaded file expired. Please re-upload the image and try again.');
            }
        });
    })->create();
