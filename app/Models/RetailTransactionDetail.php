<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailTransactionDetail extends Model
{
    protected $table = 'retail_transaction_details';

    protected $fillable = [
        'retail_transaction_id',
        'retail_product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(RetailTransaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(RetailProduct::class);
    }
}
