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
        'description',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(RetailTransaction::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getStockStatusAttribute(): string
    {
        return $this->stock > 10 ? 'In Stock' : 'Low Stock';
    }

    public function addStock(int $quantity, string $notes = null): void
    {
        $this->increment('stock', $quantity);
        $this->logMovement('add', $quantity, null, $notes);
    }

    public function deductStock(int $quantity, string $notes = null): void
    {
        $this->decrement('stock', $quantity);
        $this->logMovement('deduct', $quantity, null, $notes);
    }

    public function distributeToRaiser(int $quantity, int $raiserId, string $notes = null): void
    {
        $this->decrement('stock', $quantity);
        $this->logMovement('distribute', $quantity, $raiserId, $notes);
    }

    private function logMovement(string $type, int $quantity, ?int $raiserId = null, ?string $notes = null): void
    {
        $this->stockMovements()->create([
            'movement_type' => $type,
            'quantity' => $quantity,
            'raiser_id' => $raiserId,
            'notes' => $notes,
        ]);
    }
}

