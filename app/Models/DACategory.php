<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DACategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'severity_level',
        'percentage',
        'description',
    ];

    protected $casts = [
        'percentage' => 'integer',
    ];
}