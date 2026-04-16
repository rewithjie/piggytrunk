<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use SoftDeletes;

    protected $table = 'investments';
    
    protected $fillable = [
        'code',
        'batch_id',
        'total_amount',
        'current_value',
        'expected_profit',
        'actual_profit',
        'investment_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'roi_percentage',
        'remarks',
    ];

    protected $casts = [
        'investment_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date',
        'total_amount' => 'decimal:2',
        'current_value' => 'decimal:2',
        'expected_profit' => 'decimal:2',
        'actual_profit' => 'decimal:2',
        'roi_percentage' => 'decimal:2',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function investmentInvestors(): HasMany
    {
        return $this->hasMany(InvestmentInvestor::class);
    }
}
