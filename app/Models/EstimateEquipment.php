<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateEquipment extends Model
{
    use HasFactory;

    protected $table = 'estimate_equipment';

    protected $fillable = [
        'project_estimate_id',
        'equipment_name',
        'hours',
        'hourly_rate',
        'total'
    ];

    public function estimate()
    {
        return $this->belongsTo(ProjectEstimate::class, 'project_estimate_id');
    }
}
