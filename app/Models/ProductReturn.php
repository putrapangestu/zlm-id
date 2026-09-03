<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductReturn extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'return_number',
        'return_type',
        'order_id',
        'order_item_id',
        'restock_id',
        'restock_item_id',
        'supplier_name',
        'supplier_phone',
        'user_id',
        'product_item_id',
        'reason',
        'customer_notes',
        'proof_images',
        'status',
        'resolution_type',
        'refund_amount',
        'stock_action',
        'processed_by',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'proof_images' => 'array',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProductReturn $return) {
            if (empty($return->return_number)) {
                $prefix = $return->return_type === 'supplier' ? 'RETSUP-' : 'RET-';
                $return->return_number = $prefix . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function restock(): BelongsTo
    {
        return $this->belongsTo(Restock::class);
    }

    public function restockItem(): BelongsTo
    {
        return $this->belongsTo(RestockItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productItem(): BelongsTo
    {
        return $this->belongsTo(ProductItem::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopeCustomerReturns($query)
    {
        return $query->where('return_type', 'customer');
    }

    public function scopeSupplierReturns($query)
    {
        return $query->where('return_type', 'supplier');
    }
}
