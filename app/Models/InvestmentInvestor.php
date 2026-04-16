<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentInvestor extends Model
{
    protected $table = 'investment_investors';

    protected $fillable = [
        'investment_id',
        'investor_id',
        'amount_invested',
        'amount_returned',
        'remarks',
    ];

    protected $casts = [
        'amount_invested' => 'decimal:2',
        'amount_returned' => 'decimal:2',
    ];

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class);
    }
}
