<?php

namespace App\Services;

use App\Models\CyberSecurityLog;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\RateLimiter;

class CyberSecurityService
{
    public function detectProtections(): array
    {
        $hasSecurityHeaders = class_exists(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $hasThrottle = false;
        try {
            $hasThrottle = RateLimiter::limiter('web') !== null;
        } catch (\Throwable) {
        }

        return [
            'sql_injection' => true,
            'csrf' => true,
            'auth' => true,
            'rate_limiting' => $hasThrottle,
            'security_headers' => $hasSecurityHeaders,
            'input_validation' => true,
        ];
    }

    public static function parseUserAgent(?string $userAgent): string
    {
        if (! $userAgent || trim($userAgent) === '') {
            return '—';
        }

        $ua = ' '.$userAgent.' ';
        $os = 'غير معروف';
        $browser = '';

        if (preg_match('/\bWindows NT (\d+\.\d+)\b/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/\bWindows\b/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/\bMac OS X\b/i', $ua) || preg_match('/\bMacintosh\b/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/\biPhone\b|\biPad\b/i', $ua)) {
            $os = preg_match('/\biPad\b/i', $ua) ? 'iPad' : 'iPhone';
        } elseif (preg_match('/\bAndroid\b/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/\bLinux\b/i', $ua)) {
            $os = 'Linux';
        }

        if (preg_match('/\bEdg\/?\d+/i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/\bChrome\/\d+/i', $ua) && ! preg_match('/\bEdg\b/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/\bFirefox\/\d+/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/\bSafari\/\d+/i', $ua) && ! preg_match('/\bChrome\b/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/\bOPR\/|\bOpera\b/i', $ua)) {
            $browser = 'Opera';
        }

        return $browser ? "{$os} / {$browser}" : $os;
    }

    public function calculateSecurityScore(array $vulnerabilities, array $protections): int
    {
        $config = config('cyber.score');
        $score = $config['base'] ?? 100;

        foreach ($vulnerabilities as $vuln) {
            $status = strtolower($vuln['status'] ?? 'open');
            if ($status !== 'open') {
                continue;
            }
            $risk = strtolower($vuln['risk'] ?? 'low');
            $penalty = $config['vulnerability_penalty'][$risk] ?? 0;
            $score -= $penalty;
        }

        foreach ($protections as $key => $enabled) {
            if (! $enabled) {
                $score -= $config['missing_protection_penalty'] ?? 5;
            }
        }

        return max(0, min(100, $score));
    }

    public function summarizeLogs(): array
    {
        $recentFrom = CarbonImmutable::now()->subDays(7);

        $recentLogs = CyberSecurityLog::query()
            ->where('created_at', '>=', $recentFrom)
            ->latest()
            ->limit(50)
            ->get();

        $topIps = CyberSecurityLog::query()
            ->selectRaw('ip_address, COUNT(*) as attempts, MAX(created_at) as last_activity')
            ->whereNotNull('ip_address')
            ->groupBy('ip_address')
            ->orderByDesc('attempts')
            ->limit(10)
            ->get();

        $topIps = self::enrichTopIpsWithDevice($topIps);

        return [
            'recent_logs' => $recentLogs,
            'top_ips' => $topIps,
            'total_logs' => CyberSecurityLog::count(),
            'recent_high_risk' => CyberSecurityLog::query()
                ->where('created_at', '>=', $recentFrom)
                ->whereIn('risk_level', ['high', 'critical'])
                ->count(),
        ];
    }

    protected static function enrichTopIpsWithDevice(Collection $topIps): Collection
    {
        $ips = $topIps->pluck('ip_address')->filter()->unique()->values()->all();
        if ($ips === []) {
            return $topIps;
        }

        $latestLogs = CyberSecurityLog::query()
            ->whereIn('ip_address', $ips)
            ->select('ip_address', 'user_agent')
            ->orderByDesc('created_at')
            ->get()
            ->unique('ip_address');

        $deviceByIp = $latestLogs->mapWithKeys(function ($log) {
            return [$log->ip_address => self::parseUserAgent($log->user_agent)];
        });

        return $topIps->map(function ($row) use ($deviceByIp) {
            $row->device_os = $deviceByIp[$row->ip_address] ?? '—';

            return $row;
        });
    }
}
