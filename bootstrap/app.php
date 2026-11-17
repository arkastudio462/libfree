<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);

        // Baseline security headers for every HTML and API response.
        $middleware->web(append: [SecurityHeaders::class]);

        // Behind a reverse proxy set TRUSTED_PROXIES to the proxy IPs (or
        // "*" when the proxy is the only entry point) so HTTPS/session
        // detection and rate limiting see the real client address. When the
        // config cache is enabled, define this as a real system environment
        // variable (Laravel does not load .env in that mode).
        $trustedProxies = env('TRUSTED_PROXIES');

        if (filled($trustedProxies)) {
            $middleware->trustProxies(
                at: trim((string) $trustedProxies) === '*'
                    ? '*'
                    : array_map('trim', explode(',', (string) $trustedProxies)),
            );
        }

        // Redirect browser guests to the SPA login page instead of the
        // framework's default (nonexistent) `login` route.
        $middleware->redirectGuestsTo(fn () => route('masuk'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
