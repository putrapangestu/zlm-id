<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    protected $fillable = [
        'model_id',
        'model_type',
        'image',
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
