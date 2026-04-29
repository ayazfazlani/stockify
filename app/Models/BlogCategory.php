<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function publishedPosts()
    {
        return $this->hasMany(BlogPost::class)->where('status', 'published');
    }

    public function getUrlAttribute(): string
    {
        return url('/blog/category/' . $this->slug);
    }
}
