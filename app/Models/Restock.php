<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Restock extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'restock_number',
        'supplier_name',
        'supplier_phone',
        'invoice_number',
        'purchase_date',
        'total_amount',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Restock $restock) {
            if (empty($restock->restock_number)) {
                $restock->restock_number = 'RST-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RestockItem::class);
    }

    public function productItems(): HasMany
    {
        return $this->hasMany(ProductItem::class);
    }
}
