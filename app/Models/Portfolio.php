<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'client_name',
        'location',
        'project_type',
        'start_date',
        'completion_date',
        'budget_range',
        'featured_image',
        'image_url',
        'gallery',
        'before_after',
        'categories',
        'client_testimonial',
        'client_position',
        'project_details',
        'total_budget',
        'expected_revenue',
        'received_payment',
        'pending_payment',
        'project_profit',
        'order',
        'is_featured',
        'status',
        'execution_status',
        'manager_id',
        'is_archived',
        'meta_description',
        'meta_keywords',
        'is_public',
        // Case Study fields
        'case_study_category',
        'case_study_scope',
        'case_study_sector',
        'cs_phase_1',
        'cs_phase_2',
        'cs_phase_3',
        'cs_phase_4',
        'cs_phase_5',
        'cs_duration_weeks',
        'cs_team',
        'cs_total_value',
        'is_public_visible',
        'structural_features',
        'base_structure',
        'foundation_type',
        'total_floors',
        'floor_composition',
        'capabilities',
        'functional_features',
        'technology_used',
        'construction_technology',
        'tools_used',
        'framework_type',
    ];

    protected $casts = [
        'gallery' => 'array',
        'before_after' => 'array',
        'categories' => 'array',
        'project_details' => 'array',
        'start_date' => 'date',
        'completion_date' => 'date',
        'total_budget' => 'decimal:2',
        'expected_revenue' => 'decimal:2',
        'received_payment' => 'decimal:2',
        'pending_payment' => 'decimal:2',
        'project_profit' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_archived' => 'boolean',
        'is_public' => 'boolean',
        'is_public_visible' => 'boolean',
        'structural_features' => 'array',
        'capabilities' => 'array',
        'functional_features' => 'array',
        'tools_used' => 'array',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            if (!$portfolio->slug) {
                $portfolio->slug = Str::slug($portfolio->title);
            }
        });

        static::saving(function ($portfolio) {
            $portfolio->pending_payment = ($portfolio->expected_revenue ?? 0) - ($portfolio->received_payment ?? 0);
            $portfolio->project_profit = ($portfolio->received_payment ?? 0) - ($portfolio->total_budget ?? 0);
        });
    }
}
