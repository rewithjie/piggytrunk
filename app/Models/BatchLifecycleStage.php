<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchLifecycleStage extends Model
{
    protected $table = 'batch_lifecycle_stages';
    
    protected $fillable = [
        'name',
        'sequence',
        'duration_days',
    ];

    public $timestamps = true;
}
