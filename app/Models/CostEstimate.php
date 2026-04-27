<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostEstimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'project_type',
        'square_footage',
        'selected_options',
        'estimated_cost',
        'calculation_breakdown',
        'email',
        'phone',
        'pdf_generated',
        'pdf_path'
    ];

    protected $casts = [
        'selected_options' => 'array',
        'calculation_breakdown' => 'array',
        'estimated_cost' => 'float',
        'square_footage' => 'float',
        'pdf_generated' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
