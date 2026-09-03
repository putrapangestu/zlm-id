<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'order_number',
        'source', // online, pos
        'pos_device_id',
        'cashier_id',
        'client_order_uuid',
        'client_created_at',
        'status',
        'subtotal',
        'tax',
        'total',
        'discount_amount',
        'member_discount_amount',
        'points_earned',
        'points_used',
        'payment_method',
        'payment_status',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'xendit_expiry',
        'proof_of_transfer',
        'paid_at',
        'approved_by',
        'shipping_cost',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
        'shipping_city_id',
        'shipping_city_name',
        'shipping_province_name',
        'notes',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_phone',
        'tracking_number',
        'tracking_history',
        'shipped_at',
        'estimated_delivery',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'member_discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'xendit_expiry' => 'datetime',
        'paid_at' => 'datetime',
        'tracking_history' => 'array',
        'shipped_at' => 'datetime',
        'estimated_delivery' => 'date',
        'client_created_at' => 'datetime',
        'points_earned' => 'integer',
        'points_used' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $prefix = ($order->source === 'pos') ? 'POS-' : 'ORD-';
                $order->order_number = $prefix . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function posDevice(): BelongsTo
    {
        return $this->belongsTo(PosDevice::class, 'pos_device_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function addTrackingEvent(string $status, string $description, ?string $location = null): void
    {
        $history = $this->tracking_history ?? [];
        $history[] = [
            'status' => $status,
            'description' => $description,
            'location' => $location,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->update(['tracking_history' => $history]);
    }

    public function getLatestTracking(): ?array
    {
        $history = $this->tracking_history ?? [];
        return !empty($history) ? end($history) : null;
    }
}
