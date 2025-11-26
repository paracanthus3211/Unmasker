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
        'title',
        'slug',
        'image',
        'excerpt',
        'content',
        'user_id', // user_id bisa nullable sekarang
        'is_published',
        'views_count'
    ];

    protected $attributes = [
        'is_published' => true,
        'views_count' => 0,
        'user_id' => 1 // Default user ID
    ];

    /**
     * Boot function for automatic slug generation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            // Auto generate slug
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
                
                // Handle duplicate slugs
                $count = 1;
                while (static::where('slug', $article->slug)->exists()) {
                    $article->slug = Str::slug($article->title) . '-' . $count;
                    $count++;
                }
            }

            // Set default user_id jika tidak ada
            if (empty($article->user_id)) {
                $article->user_id = 1; // User ID default
            }
        });
    }

    /**
     * Get the user that owns the article
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}