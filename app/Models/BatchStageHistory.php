<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchStageHistory extends Model
{
    protected $table = 'batch_stage_history';

    protected $fillable = [
        'batch_id',
        'lifecycle_stage_id',
        'started_at',
        'completed_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function lifecycleStage(): BelongsTo
    {
        return $this->belongsTo(BatchLifecycleStage::class);
    }
}
