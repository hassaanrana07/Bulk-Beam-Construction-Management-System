<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateLabor extends Model
{
    use HasFactory;

    protected $table = 'estimate_labors';

    protected $fillable = [
        'project_estimate_id',
        'worker_type',
        'count',
        'days',
        'daily_rate',
        'total'
    ];

    public function estimate()
    {
        return $this->belongsTo(ProjectEstimate::class, 'project_estimate_id');
    }
}
