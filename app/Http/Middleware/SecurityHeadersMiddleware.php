<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        $csp = $this->buildContentSecurityPolicy();
        if ($csp !== null) {
            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }

    protected function buildContentSecurityPolicy(): ?string
    {
        $directives = config('security-headers.csp', [
            'default-src' => ["'self'"],
            'script-src' => ["'self'", 'https://cdn.tailwindcss.com', 'https://cdn.jsdelivr.net'],
            'style-src' => ["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com', 'https://cdn.tailwindcss.com'],
            'font-src' => ["'self'", 'https://fonts.gstatic.com'],
            'img-src' => ["'self'", 'data:', 'https:'],
            'frame-src' => ["'self'", 'https:'],
            'connect-src' => ["'self'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'object-src' => ["'none'"],
            'frame-ancestors' => ["'self'"],
        ]);

        if (config('app.env') !== 'production' && isset($directives['script-src']) && is_array($directives['script-src'])) {
            if (! in_array("'unsafe-inline'", $directives['script-src'], true)) {
                $directives['script-src'][] = "'unsafe-inline'";
            }
            if (! in_array("'unsafe-eval'", $directives['script-src'], true)) {
                $directives['script-src'][] = "'unsafe-eval'";
            }
        }

        $parts = [];
        foreach ($directives as $name => $sources) {
            if (is_array($sources)) {
                $parts[] = $name.' '.implode(' ', $sources);
            } else {
                $parts[] = $name.' '.$sources;
            }
        }

        return implode('; ', $parts) ?: null;
    }
}
