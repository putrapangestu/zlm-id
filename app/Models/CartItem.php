<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'cart_id',
        'laptop_id',
        'laptop_variant_id',
        'addon_id',
        'addon_price',
        'quantity',
        'unit_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'addon_price' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
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

    public function getSubtotalAttribute(): float
    {
        return ($this->unit_price + ($this->addon_price ?? 0)) * $this->quantity;
    }
}
