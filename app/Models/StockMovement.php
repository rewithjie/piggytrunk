<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'retail_product_id',
        'movement_type',
        'quantity',
        'raiser_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function retailProduct(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class);
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(Raiser::class);
    }

    public function getMovementLabelAttribute(): string
    {
        return match($this->movement_type) {
            'add' => 'Stock In',
            'deduct' => 'Sale',
            'distribute' => 'Distributed to Raiser',
            default => ucfirst($this->movement_type),
        };
    }
}
