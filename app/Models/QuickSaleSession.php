<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuickSaleSession extends Model
{
    protected $fillable = [
        'session_key',
        'total_amount',
        'discount_amount',
        'net_amount',
        'status',
        'payment_method',
        'customer_name',
        'raiser_id',
        'remarks',
        'created_by',
        'confirmed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(QuickSaleItem::class);
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(Raiser::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateTotals(): void
    {
        $this->total_amount = $this->items()->sum('total_price');
        $this->discount_amount = $this->items()->sum('discount_amount');
        $this->net_amount = $this->items()->sum('net_price');
        $this->save();
    }

    public function confirm(): void
    {
        if ($this->status !== 'draft') {
            throw new \Exception('Can only confirm draft quick sales');
        }

        $this->status = 'completed';
        $this->confirmed_at = now();
        $this->save();

        // Create retail transactions for each item
        foreach ($this->items as $item) {
            RetailTransaction::create([
                'code' => 'QS-' . uniqid(),
                'retail_product_id' => $item->retail_product_id,
                'raiser_id' => $this->raiser_id,
                'quantity' => $item->quantity,
                'transaction_type' => 'sale',
                'channel' => 'Quick Sale',
                'status' => 'completed',
                'total_amount' => $item->total_price,
                'discount_amount' => $item->discount_amount,
                'net_amount' => $item->net_price,
                'payment_method' => $this->payment_method ?? 'cash',
                'customer_name' => $this->customer_name,
                'remarks' => $this->remarks,
                'created_by' => $this->created_by,
            ]);

            // Deduct stock from product
            $item->product->deductStock($item->quantity, 'Quick Sale');
        }
    }

    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->save();
    }
}
