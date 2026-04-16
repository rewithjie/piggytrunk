<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $table = 'inventory_items';

    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit',
        'quantity',
        'reorder_level',
        'unit_cost',
        'status',
        'remarks',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'unit_cost' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
