<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickSaleItem extends Model
{
    protected $fillable = [
        'quick_sale_session_id',
        'retail_product_id',
        'quantity',
        'unit_price',
        'total_price',
        'discount_amount',
        'net_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_price' => 'decimal:2',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(QuickSaleSession::class, 'quick_sale_session_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class, 'retail_product_id');
    }

    public function updateQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
        $this->total_price = $this->unit_price * $quantity;
        $this->net_price = $this->total_price - $this->discount_amount;
        $this->save();

        // Recalculate session totals
        $this->session->calculateTotals();
    }

    public function updateDiscount(float $discount): void
    {
        $this->discount_amount = $discount;
        $this->net_price = $this->total_price - $discount;
        $this->save();

        // Recalculate session totals
        $this->session->calculateTotals();
    }
}
