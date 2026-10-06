<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subdistrict extends Model
{
    protected $fillable = ['city_id', 'id_rajaongkir', 'name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
