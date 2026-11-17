<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline security headers for every web response.
 *
 * The Content-Security-Policy is only emitted in production: the Vite dev
 * server needs inline/eval scripts and a websocket connection that would make
 * the policy misleading during local development.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'DENY');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $headers->set('X-XSS-Protection', '0');

        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if (app()->isProduction()) {
            $headers->set('Content-Security-Policy', $this->contentSecurityPolicy());
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "object-src 'none'",
            "frame-ancestors 'none'",
            "form-action 'self'",
            "script-src 'self'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com blob:",
            "font-src 'self' https://fonts.gstatic.com data: blob:",
            "img-src 'self' data: blob: https:",
            "connect-src 'self' blob:",
            "media-src 'self' data: blob:",
            "worker-src 'self' blob:",
            "frame-src 'self' blob:",
            'upgrade-insecure-requests',
        ]);
    }
}
