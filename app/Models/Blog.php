<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'image_url',
        'knowledge_category_id',
        'author_id',
        'author_name',
        'author_role',
        'tags',
        'seo_title',
        'seo_description',
        'reading_time',
        'is_featured',
        'status',
        'published_at',
        'is_public_visible',
        'views',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'is_public_visible' => 'boolean',
        'views' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($blog) {
            if (!$blog->slug) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function knowledgeCategory()
    {
        return $this->belongsTo(KnowledgeCategory::class, 'knowledge_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
