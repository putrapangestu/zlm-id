<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'category',
        'excerpt',
        'description',
        'author',
        'date',
        'is_published',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'date' => 'date',
        'is_published' => 'boolean',
        'views_count' => 'integer',
    ];

    protected $appends = [
        'thumbnail_url_full',
        'reading_time',
        'seo_meta_title',
        'seo_meta_description',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $slug = Str::slug($article->name);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $article->slug = $slug;
            }

            if (empty($article->date)) {
                $article->date = now();
            }
        });

        static::updating(function (Article $article) {
            if ($article->isDirty('name') && !$article->isDirty('slug')) {
                $slug = Str::slug($article->name);
                $originalSlug = $slug;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $article->slug = $slug;
            }
        });
    }

    public function getThumbnailUrlFullAttribute(): string
    {
        if (!$this->thumbnail) {
            return 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=1200';
        }

        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        return Storage::disk('public')->url($this->thumbnail);
    }

    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->description ?? ''));
        return max(1, (int) ceil($words / 200));
    }

    public function getSeoMetaTitleAttribute(): string
    {
        return $this->meta_title ?: "{$this->name} — ZLM.ID";
    }

    public function getSeoMetaDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        if ($this->excerpt) {
            return Str::limit(strip_tags($this->excerpt), 160);
        }

        return Str::limit(strip_tags($this->description ?? ''), 160);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeCategory($query, ?string $category)
    {
        if (!empty($category) && $category !== 'Semua') {
            return $query->where('category', $category);
        }
        return $query;
    }
}
