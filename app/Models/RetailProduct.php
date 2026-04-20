<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'cost_price',
        'selling_price',
        'price_per_sack',
        'price_per_kilo',
        'price_per_half_kilo',
        'price_per_quarter_kilo',
        'unit',
        'supplier',
        'quantity_in_stock',
        'reorder_level',
        'image_path',
        'status',
        'remarks',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'price_per_sack' => 'decimal:2',
        'price_per_kilo' => 'decimal:2',
        'price_per_half_kilo' => 'decimal:2',
        'price_per_quarter_kilo' => 'decimal:2',
        'quantity_in_stock' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function getPriceAttribute()
    {
        return $this->selling_price ?? 0;
    }

    public function setPriceAttribute($value)
    {
        $this->selling_price = $value;
    }

    public function getStockAttribute()
    {
        return $this->quantity_in_stock ?? 0;
    }

    public function setStockAttribute($value)
    {
        $this->quantity_in_stock = $value;
    }

    public function getImageAttribute()
    {
        return $this->image_path ?? null;
    }

    public function setImageAttribute($value)
    {
        $this->image_path = $value;
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(RetailTransaction::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function quickSaleItems(): HasMany
    {
        return $this->hasMany(QuickSaleItem::class, 'retail_product_id');
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

