<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'model_type',
        'model_id',
        'collection_name',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'size',
        'manipulations',
        'custom_properties',
        'generated_conversions',
        'responsive_images',
        'order_column',
    ];

    protected $casts = [
        'manipulations' => 'json',
        'custom_properties' => 'json',
        'generated_conversions' => 'json',
        'responsive_images' => 'json',
        'size' => 'integer',
        'order_column' => 'integer',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
