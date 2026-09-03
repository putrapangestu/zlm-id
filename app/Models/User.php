<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'phone_number',
        'member_number',
        'member_tier',
        'member_points',
        'joined_member_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'joined_member_at' => 'datetime',
            'password' => 'hashed',
            'member_points' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->member_number)) {
                $user->member_number = 'MBR-' . strtoupper(substr(uniqid(), -6));
                $user->joined_member_at = now();
            }
        });
    }

    public function getTierDiscountPercentageAttribute(): float
    {
        return match (strtolower($this->member_tier ?? 'bronze')) {
            'platinum' => 5.0,
            'gold' => 3.0,
            'silver' => 1.5,
            default => 0.0,
        };
    }

    public function otps(): HasMany
    {
        return $this->hasMany(Otp::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function restocks(): HasMany
    {
        return $this->hasMany(Restock::class, 'created_by');
    }

    public function productReturns(): HasMany
    {
        return $this->hasMany(ProductReturn::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
