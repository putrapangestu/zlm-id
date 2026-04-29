<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'type',
        'description',
        'price',
        'processor',
        'ram',
        'storage',
        'graphic',
        'display',
        'battery',
        'weight',
        'minus'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'model');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function getImageUrlAttribute()
    {
        $image = $this->images()->first();
        return $image ? $image->image : 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=1200';
    }

    public function getCategoryAttribute()
    {
        return strtolower($this->type);
    }

    public function getStockAttribute()
    {
        return 10;
    }
}
