<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LaptopImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'laptop_id',
        'image_url',
        'sort_order',
        'caption',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function getImageUrlFullAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        return Storage::url($this->image_url);
    }
}
