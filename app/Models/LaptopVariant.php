<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LaptopVariant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'laptop_id',
        'name',
        'sku',
        'price_modifier',
        'ram',
        'storage',
        'graphics',
        'display',
        'weight',
        'battery_life',
        'image_url',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['image_url_full'];

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->laptop->price + $this->price_modifier;
    }

    public function getImageUrlFullAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        // Already a full URL — return as-is
        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        // Storage-relative path — convert to full URL
        return Storage::url($this->image_url);
    }
}
