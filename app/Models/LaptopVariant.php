<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class LaptopVariant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'laptop_id',
        'name',
        'sku',
        'price_modifier',
        'discount_type',
        'discount_value',
        'discount_start_at',
        'discount_end_at',
        'is_discount_active',
        'ram',
        'storage',
        'graphics',
        'display',
        'weight',
        'battery_life',
        'image_url',
        'stock',
        'uninspected_stock',
        'qc_passed_stock',
        'is_active',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'is_discount_active' => 'boolean',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'stock' => 'integer',
        'uninspected_stock' => 'integer',
        'qc_passed_stock' => 'integer',
    ];

    protected $appends = ['image_url_full', 'total_price', 'final_price', 'discount_amount', 'has_discount'];

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function productItems(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getTotalPriceAttribute(): float
    {
        $basePrice = $this->laptop ? (float) $this->laptop->price : 0;
        return $basePrice + (float) $this->price_modifier;
    }

    public function getHasDiscountAttribute(): bool
    {
        if ($this->is_discount_active && $this->discount_type !== 'none' && $this->discount_value > 0) {
            $now = now();
            if ($this->discount_start_at && $now->lt($this->discount_start_at)) {
                return false;
            }
            if ($this->discount_end_at && $now->gt($this->discount_end_at)) {
                return false;
            }
            return true;
        }

        return $this->laptop ? $this->laptop->has_discount : false;
    }

    public function getDiscountAmountAttribute(): float
    {
        if (!$this->has_discount) {
            return 0;
        }

        $totalPrice = $this->total_price;

        if ($this->is_discount_active && $this->discount_type !== 'none' && $this->discount_value > 0) {
            if ($this->discount_type === 'percentage') {
                return round(($totalPrice * $this->discount_value) / 100, 2);
            }
            return min((float) $this->discount_value, $totalPrice);
        }

        return $this->laptop ? $this->laptop->discount_amount : 0;
    }

    public function getFinalPriceAttribute(): float
    {
        return max(0, $this->total_price - $this->discount_amount);
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->qc_passed_stock > 0 ? $this->qc_passed_stock : $this->stock;
    }

    public function getImageUrlFullAttribute(): ?string
    {
        if (!$this->image_url) {
            return $this->laptop ? $this->laptop->image_url_full : null;
        }

        if (str_starts_with($this->image_url, 'http://') || str_starts_with($this->image_url, 'https://')) {
            return $this->image_url;
        }

        return Storage::url($this->image_url);
    }
}
