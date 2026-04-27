<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_position',
        'client_company',
        'client_image',
        'image_url',
        'testimonial',
        'rating',
        'project_type',
        'portfolio_id',
        'is_featured',
        'is_published',
        'is_public_visible',
        'order'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'is_public_visible' => 'boolean',
        'rating' => 'integer',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
