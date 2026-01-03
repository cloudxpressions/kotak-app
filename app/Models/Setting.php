<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    // Optionally cast certain values as arrays if they're JSON
    // protected $casts = [
    //     'value' => 'array',
    // ];
}