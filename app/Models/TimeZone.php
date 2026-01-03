<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'timezone',
        'offset',
        'utc_offset_minutes',
        'is_active',
    ];

    protected $casts = [
        'utc_offset_minutes' => 'integer',
        'is_active' => 'boolean',
    ];
}