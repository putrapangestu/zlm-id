<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function laptops(): HasMany
    {
        return $this->hasMany(Laptop::class, 'brand_id');
    }

    public function productItems(): HasManyThrough
    {
        return $this->hasManyThrough(ProductItem::class, Laptop::class, 'brand_id', 'laptop_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSorted(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getLogoUrlFullAttribute(): ?string
    {
        if (!$this->logo_url) {
            return null;
        }

        if (str_starts_with($this->logo_url, 'http://') || str_starts_with($this->logo_url, 'https://')) {
            return $this->logo_url;
        }

        return Storage::url($this->logo_url);
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->laptops()->sum('stock');
    }

    public function getSoldUnitsAttribute(): int
    {
        return (int) $this->productItems()->where('is_sold', true)->count();
    }

    public function getTotalRevenueAttribute(): float
    {
        return (float) OrderItem::whereIn('laptop_id', $this->laptops()->pluck('id'))->sum('subtotal');
    }
}
