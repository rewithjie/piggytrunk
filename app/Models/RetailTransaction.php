<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailTransaction extends Model
{
    protected $fillable = [
        'retail_product_id',
        'raiser_id',
        'customer_name',
        'quantity',
        'channel',
        'status',
        'total_amount',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date',
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

