<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'vendor_id',
        'amount',
        'category',
        'invoice_number',
        'invoice_path',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
