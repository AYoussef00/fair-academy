<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DigitalBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewed_by',
        'title',
        'cover_path',
        'price',
        'purchase_count',
        'status',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'purchase_count' => 'integer',
            'reviewed_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
