<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailTransaction extends Model
{
    protected $fillable = [
        'code',
        'retail_product_id',
        'raiser_id',
        'quantity',
        'transaction_type',
        'channel',
        'status',
        'total_amount',
        'discount_amount',
        'net_amount',
        'payment_method',
        'customer_name',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class, 'retail_product_id');
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(Raiser::class);
    }
}

