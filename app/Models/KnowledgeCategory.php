<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KnowledgeCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status', 'description', 'order'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'knowledge_category_id');
    }
}
