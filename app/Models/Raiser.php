<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raiser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'code',
        'phone',
        'email',
        'address',
        'pig_type_id',
        'capacity',
        'status',
        'location',
        'contact_person',
        'total_capacity',
        'total_investment',
    ];

    protected $casts = [
        'total_investment' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pigType(): BelongsTo
    {
        return $this->belongsTo(PigType::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name)) ?: [];

        return collect($parts)
            ->take(2)
            ->map(fn (string $part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
    }

    public function getAccentAttribute(): string
    {
        return match ($this->status) {
            'active' => match ($this->location ?? 'default') {
                'Bulacan' => 'rose',
                'Pampanga' => 'sky',
                default => 'sky',
            },
            'Active' => match ($this->location ?? 'default') {
                'Bulacan' => 'rose',
                'Pampanga' => 'sky',
                default => 'sky',
            },
            default => 'slate',
        };
    }
}
