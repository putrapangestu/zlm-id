<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    protected $fillable = [
        'name',
        'brand',
        'description',
        'price',
        'processor',
        'ram',
        'storage',
        'graphics',
        'display',
        'weight',
        'battery_life',
        'image_url',
        'category',
        'stock',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeInPriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }
}
