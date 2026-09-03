<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestockItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'restock_id',
        'laptop_id',
        'laptop_variant_id',
        'quantity',
        'purchase_price',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class);
    }

    public function laptop(): BelongsTo
    {
        return $this->belongsTo(Laptop::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(LaptopVariant::class, 'laptop_variant_id');
    }
}
