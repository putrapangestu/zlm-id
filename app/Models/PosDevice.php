<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PosDevice extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'device_code',
        'device_name',
        'device_uuid',
        'last_sync_at',
        'status',
    ];

    protected $casts = [
        'last_sync_at' => 'datetime',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
