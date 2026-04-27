<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'role',
        'bio',
        'photo',
        'photo_url',
        'social_links',
        'order',
        'is_active',
        'is_leadership',
        'is_public_visible',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
        'is_leadership' => 'boolean',
        'is_public_visible' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($staff) {
            if (empty($staff->slug)) {
                $staff->slug = Str::slug($staff->name);
            }
        });
    }
}
