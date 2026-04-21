<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'item_id',
        'title',
        'price',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'quantity' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function digitalBook(): BelongsTo
    {
        return $this->belongsTo(DigitalBook::class, 'item_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'item_id');
    }
}
