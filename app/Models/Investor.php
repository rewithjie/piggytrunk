<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    use SoftDeletes;

    protected $table = 'investors';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'type',
        'status',
        'total_invested',
        'total_returned',
    ];

    protected $casts = [
        'total_invested' => 'decimal:2',
        'total_returned' => 'decimal:2',
    ];
}
