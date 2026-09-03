<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_id',
        'laptop_id',
        'laptop_variant_id',
        'addon_id',
        'addon_name',
        'addon_price',
        'product_item_id',
        'sku',
        'product_name',
        'variant_name',
        'quantity',
        'unit_price',
        'discount_amount',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'addon_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(LaptopVariant::class, 'laptop_variant_id');
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }
}
