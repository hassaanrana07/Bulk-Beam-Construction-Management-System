<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'user_id',
        'title',
        'file_path',
        'type',
        'visibility_roles',
    ];

    protected $casts = [
        'visibility_roles' => 'array',
    ];

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
