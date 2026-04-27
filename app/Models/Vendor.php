<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'category',
        'address',
        'company_bim',
        'company_bic',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
