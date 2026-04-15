<?php

namespace App\Http\Middleware;

use App\Models\CyberSecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityMonitoringMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->detectInjectionPatterns($request);

        return $response;
    }

    protected function detectInjectionPatterns(Request $request): void
    {
        $payload = $request->all();
        $flat = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($flat === false || $flat === 'null') {
            return;
        }

        $eventType = null;
        $risk = 'low';

        $sqlPatterns = [
            '/\bunion\b\s+select\b/i',
            '/(?:\'|\")\s*or\s+1=1/i',
            '/\binformation_schema\b/i',
        ];

        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $flat)) {
                $eventType = 'sql_injection_attempt';
                $risk = 'high';
                break;
            }
        }

        if ($eventType === null) {
            $xssPatterns = [
                '/<script\b/i',
                '/onerror\s*=/i',
                '/javascript:/i',
            ];
            foreach ($xssPatterns as $pattern) {
                if (preg_match($pattern, $flat)) {
                    $eventType = 'xss_attempt';
                    $risk = 'high';
                    break;
                }
            }
        }

        if ($eventType !== null) {
            CyberSecurityLog::create([
                'ip_address' => $request->ip(),
                'user_id' => optional($request->user())->id,
                'event_type' => $eventType,
                'risk_level' => $risk,
                'endpoint' => $request->method().' '.$request->path(),
                'user_agent' => $request->userAgent() ? mb_substr($request->userAgent(), 0, 500) : null,
                'payload' => mb_substr($flat, 0, 2000),
            ]);
        }
    }
}
