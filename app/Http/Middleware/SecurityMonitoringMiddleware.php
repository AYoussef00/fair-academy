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
        $threat = $this->detectInjectionPatterns($request);
        if ($threat !== null) {
            $this->logThreat($request, $threat);

            if (($threat['risk_level'] ?? 'low') === 'high') {
                return $this->blockRequest($request);
            }
        }

        $response = $next($request);

        return $response;
    }

    /**
     * @return array{event_type:string,risk_level:string,payload:string}|null
     */
    protected function detectInjectionPatterns(Request $request): ?array
    {
        $payload = [
            'query' => $request->query(),
            'body' => $request->request->all(),
            'route' => $request->route()?->parameters() ?? [],
            'path' => $request->path(),
            'query_string' => $request->getQueryString(),
        ];

        $flat = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($flat === false || $flat === 'null') {
            return null;
        }

        $eventType = null;
        $risk = 'low';

        $sqlPatterns = [
            '/\bunion(?:\s+all)?\s+select\b/i',
            '/(?:\'|")\s*(?:or|and)\s+(?:\d+|\'[^\']+\')\s*=\s*(?:\d+|\'[^\']+\')/i',
            '/\binformation_schema\b/i',
            '/\b(?:sleep|benchmark)\s*\(/i',
            '/;\s*(?:drop|truncate|delete|insert|update)\b/i',
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
                '/on\w+\s*=/i',
                '/javascript:/i',
                '/<iframe\b/i',
                '/data:\s*text\/html/i',
            ];
            foreach ($xssPatterns as $pattern) {
                if (preg_match($pattern, $flat)) {
                    $eventType = 'xss_attempt';
                    $risk = 'high';
                    break;
                }
            }
        }

        if ($eventType === null) {
            return null;
        }

        return [
            'event_type' => $eventType,
            'risk_level' => $risk,
            'payload' => mb_substr($flat, 0, 2000),
        ];
    }

    protected function logThreat(Request $request, array $threat): void
    {
        CyberSecurityLog::create([
            'ip_address' => $request->ip(),
            'user_id' => optional($request->user())->id,
            'event_type' => (string) ($threat['event_type'] ?? 'security_event'),
            'risk_level' => (string) ($threat['risk_level'] ?? 'low'),
            'endpoint' => $request->method().' '.$request->path(),
            'user_agent' => $request->userAgent() ? mb_substr($request->userAgent(), 0, 500) : null,
            'payload' => (string) ($threat['payload'] ?? ''),
        ]);
    }

    protected function blockRequest(Request $request): Response
    {
        $message = 'تم رفض الطلب لأسباب أمنية.';

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return response($message, 422);
    }
}
