<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'icon',
        'featured_image',
        'image_url',
        'gallery',
        'benefits',
        'process_steps',
        'faq',
        'order',
        'is_featured',
        'status',
        'meta_description',
        'meta_keywords',
        'is_public',
        // Product Structure fields
        'structure_description',
        'timeline_summary',
        'phases_details',
        'technical_layout_image',
        // Operations/Vacations fields
        'operations_description',
        'operations_bullets',
        'operations_timeline',
        'operations_team',
        'vacations_description',
        'vacations_bullets',
        'vacations_timeline',
        // Capability Scope
        'capability_features',
        'capability_deliverables',
        'capability_exclusions',
        'capability_tools',
        'capability_scope_description',
        // Product Structure Analysis
        'structural_type',
        'technical_breakdown',
        'materials_used',
        'architecture_layout',
        'is_public_visible',
    ];

    protected $casts = [
        'gallery' => 'array',
        'benefits' => 'array',
        'process_steps' => 'array',
        'faq' => 'array',
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'phases_details' => 'array',
        'operations_bullets' => 'array',
        'vacations_bullets' => 'array',
        'capability_features' => 'array',
        'capability_deliverables' => 'array',
        'capability_exclusions' => 'array',
        'capability_tools' => 'array',
        'materials_used' => 'array',
        'architecture_layout' => 'array',
        'is_public_visible' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($service) {
            if (!$service->slug) {
                $service->slug = Str::slug($service->title);
            }
        });
    }
}
