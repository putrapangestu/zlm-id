<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'restock_id',
        'laptop_id',
        'laptop_variant_id',
        'sku',
        'serial_number',
        'qc_status',
        'is_sold',
        'qc_checklist',
        'qc_notes',
        'qc_by',
        'qc_at',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
        'qc_checklist' => 'array',
        'qc_at' => 'datetime',
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

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qc_by');
    }

    public function scopePending($query)
    {
        return $query->where('qc_status', 'pending');
    }

    public function scopePassed($query)
    {
        return $query->where('qc_status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('qc_status', 'failed');
    }

    public function scopeAvailableForSale($query)
    {
        return $query->where('qc_status', 'passed')
            ->whereNotNull('sku')
            ->where('is_sold', false);
    }

    public function scopeInStock($query)
    {
        return $query->where('is_sold', false);
    }

    public function scopeSold($query)
    {
        return $query->where('is_sold', true);
    }
}
