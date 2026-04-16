<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PigType extends Model
{
    protected $table = 'pig_types';
    
    protected $fillable = [
        'name',
        'description',
        'code',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class, 'pig_type_id');
    }
}
