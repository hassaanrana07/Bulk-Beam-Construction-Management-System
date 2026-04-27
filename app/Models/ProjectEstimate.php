<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEstimate extends Model
{
    protected $fillable = [
        'portfolio_id',
        'project_title',
        'reference_number',
        'estimate_date',
        'material_cost',
        'labor_cost',
        'equipment_cost',
        'other_cost',
        'other_costs_details',
        'tax_percent',
        'contingency_percent',
        'profit_percent',
        'total_amount',
        'status',
        'created_by'
    ];

    protected $casts = [
        'estimate_date' => 'date',
        'other_costs_details' => 'array',
        'tax_percent' => 'float',
        'contingency_percent' => 'float',
        'profit_percent' => 'float',
        'material_cost' => 'float',
        'labor_cost' => 'float',
        'equipment_cost' => 'float',
        'other_cost' => 'float',
        'total_amount' => 'float',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
        return $this->hasMany(EstimateMaterial::class);
    }

    public function labor()
    {
        return $this->hasMany(EstimateLabor::class);
    }

    public function equipment()
    {
        return $this->hasMany(EstimateEquipment::class);
    }
}
