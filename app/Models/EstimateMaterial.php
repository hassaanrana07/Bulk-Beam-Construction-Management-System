<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_estimate_id',
        'item_name',
        'description',
        'quantity',
        'unit',
        'unit_cost',
        'total'
    ];

    public function estimate()
    {
        return $this->belongsTo(ProjectEstimate::class, 'project_estimate_id');
    }
}
