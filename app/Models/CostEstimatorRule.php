<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostEstimatorRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'sector',
        'scope_level',
        'base_rate_per_sqft',
        'material_cost_factor',
        'labor_cost_factor',
        'sector_multipliers',
        'complexity_multipliers',
        'timeline_weeks_per_1000sqft',
        'multipliers',
        'options',
        'is_active',
        'order'
    ];

    protected $casts = [
        'multipliers' => 'array',
        'options' => 'array',
        'sector_multipliers' => 'array',
        'complexity_multipliers' => 'array',
        'is_active' => 'boolean',
        'base_rate_per_sqft' => 'float',
        'material_cost_factor' => 'float',
        'labor_cost_factor' => 'float',
        'timeline_weeks_per_1000sqft' => 'integer',
    ];
}
