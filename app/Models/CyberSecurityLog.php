<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CyberSecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_id',
        'event_type',
        'risk_level',
        'endpoint',
        'user_agent',
        'payload',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
