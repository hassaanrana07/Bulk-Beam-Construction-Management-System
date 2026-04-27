<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issuing_organization',
        'certificate_number',
        'image',
        'issue_date',
        'expiry_date',
        'description',
        'is_active',
        'is_public_visible',
        'order',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
        'is_public_visible' => 'boolean',
        'order' => 'integer',
    ];
}
