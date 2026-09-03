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
        'brand_id',
        'description',
        'price',
        'discount_type',
        'discount_value',
        'discount_start_at',
        'discount_end_at',
        'is_discount_active',
        'processor',
        'ram',
        'storage',
        'graphics',
        'display',
        'ports',
        'camera',
        'audio',
        'connectivity',
        'color',
        'warranty',
        'weight',
        'battery_life',
        'image_url',
        'kelebihan',
        'kekurangan',
        'stock',
        'uninspected_stock',
        'qc_passed_stock',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
        'is_discount_active' => 'boolean',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'stock' => 'integer',
        'uninspected_stock' => 'integer',
        'qc_passed_stock' => 'integer',
    ];

    protected $appends = [
        'image_url_full',
        'final_price',
        'discount_amount',
        'has_discount',
        'is_sold',
        'stock_status',
        'stock_status_label'
    ];

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

    public function brandRelation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(LaptopVariant::class);
    }

    public function productItems(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
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

    public function getHasDiscountAttribute(): bool
    {
        $isActive = $this->is_discount_active ?? true;
        if (!$isActive || empty($this->discount_type) || $this->discount_type === 'none' || (float)$this->discount_value <= 0) {
            return false;
        }

        $now = now();
        if ($this->discount_start_at && $now->lt($this->discount_start_at)) {
            return false;
        }
        if ($this->discount_end_at && $now->gt($this->discount_end_at)) {
            return false;
        }

        return true;
    }

    public function getDiscountAmountAttribute(): float
    {
        if (!$this->has_discount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return round(($this->price * $this->discount_value) / 100, 2);
        }

        return min((float) $this->discount_value, (float) $this->price);
    }

    public function getFinalPriceAttribute(): float
    {
        return max(0, (float) $this->price - $this->discount_amount);
    }

    public function getAvailableStockAttribute(): int
    {
        return $this->qc_passed_stock > 0 ? $this->qc_passed_stock : $this->stock;
    }

    public function getIsSoldAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'sold_out';
        }
        if ($this->stock <= 2) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'sold_out' => 'Habis Terjual',
            'low_stock' => 'Stok Menipis (' . $this->stock . ')',
            default => 'Tersedia (' . $this->stock . ' Unit)',
        };
    }

    public function getPortsListAttribute(): array
    {
        if (empty($this->ports)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->ports)))));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeSoldOut($query)
    {
        return $query->where('stock', '<=', 0);
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
