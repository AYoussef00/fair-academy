<?php

namespace App\Listeners;

use App\Models\CyberSecurityLog;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class LogSuspiciousAuthActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(object $event): void
    {
        if ($event instanceof Failed) {
            $this->log(
                ip: request()->ip(),
                userId: optional($event->user)->id,
                eventType: 'login_failed',
                risk: 'medium',
                endpoint: 'auth.login',
                payload: Arr::only($event->credentials, ['email']),
                userAgent: request()->userAgent(),
            );
        } elseif ($event instanceof Lockout) {
            $this->log(
                ip: $event->request->ip(),
                userId: null,
                eventType: 'auth_lockout',
                risk: 'high',
                endpoint: 'auth.lockout',
                payload: ['url' => $event->request->fullUrl()],
                userAgent: $event->request->userAgent(),
            );
        }
    }

    protected function log(?string $ip, ?int $userId, string $eventType, string $risk, string $endpoint, array $payload = [], ?string $userAgent = null): void
    {
        CyberSecurityLog::create([
            'ip_address' => $ip,
            'user_id' => $userId,
            'event_type' => $eventType,
            'risk_level' => $risk,
            'endpoint' => $endpoint,
            'user_agent' => $userAgent ? mb_substr($userAgent, 0, 500) : null,
            'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);
    }
}
