<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raiser extends Model
{
    protected $fillable = [
        'code',
        'name',
        'location',
        'batch',
        'pig_type',
        'status',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

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
            'Active' => match ($this->location) {
                'Bulacan' => 'rose',
                'Pampanga' => 'sky',
                default => 'sky',
            },
            default => 'slate',
        };
    }
}
