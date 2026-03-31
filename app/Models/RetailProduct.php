<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RetailProduct extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(RetailTransaction::class);
    }

    public function getStockStatusAttribute(): string
    {
        return $this->stock > 10 ? 'In Stock' : 'Low Stock';
    }
}

