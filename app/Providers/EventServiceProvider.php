<?php

namespace App\Providers;

use App\Listeners\LogSuspiciousAuthActivity;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Failed::class => [
            LogSuspiciousAuthActivity::class,
        ],
        Lockout::class => [
            LogSuspiciousAuthActivity::class,
        ],
    ];

    public function boot(): void {}
}
