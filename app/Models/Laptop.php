<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Laptop extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'brand',
        'description',
        'price',
        'processor',
        'ram',
        'storage',
        'graphics',
        'display',
        'weight',
        'battery_life',
        'image_url',
        'kelebihan',
        'kekurangan',
        'stock',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    protected $appends = ['image_url_full'];

    protected static function booted(): void
    {
        static::creating(function (Laptop $laptop) {
            if (empty($laptop->slug)) {
                $laptop->slug = Str::slug($laptop->name);
            }
        });

        static::updating(function (Laptop $laptop) {
            if ($laptop->isDirty('name') && !$laptop->isDirty('slug')) {
                $laptop->slug = Str::slug($laptop->name);
            }
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(LaptopVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(LaptopImage::class)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}
