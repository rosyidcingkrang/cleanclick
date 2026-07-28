<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Trust proxies untuk HTTPS Railway
        $middleware->trustProxies(at: '*');

        // 2. Daftarkan alias middleware 'role' di sini:
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tangani 419 (CSRF token expired) dengan redirect + pesan,
        // bukan halaman kosong yang membingungkan admin.
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->back()
                ->with('error', 'Sesi Anda telah berakhir. Silakan coba lagi.');
        });
    })->create();