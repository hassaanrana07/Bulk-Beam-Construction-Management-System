<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order',
        'is_published',
        'is_public_visible'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_public_visible' => 'boolean',
        'order' => 'integer'
    ];
}
