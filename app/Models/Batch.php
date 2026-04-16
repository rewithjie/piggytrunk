<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $table = 'batches';
    
    protected $fillable = [
        'code',
        'raiser_id',
        'pig_type_id',
        'initial_quantity',
        'current_quantity',
        'start_date',
        'end_date',
        'status',
        'total_investment',
        'expected_profit',
        'remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_investment' => 'decimal:2',
        'expected_profit' => 'decimal:2',
    ];

    public function raiser(): BelongsTo
    {
        return $this->belongsTo(Raiser::class);
    }

    public function pigType(): BelongsTo
    {
        return $this->belongsTo(PigType::class, 'pig_type_id');
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(BatchStageHistory::class);
    }
}
