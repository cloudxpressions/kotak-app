<?php

namespace App\Models;

class Material extends Model
{
    protected $table = 'materials';
    protected $fillable = [
        'type',
        'file_path'
    ];
}