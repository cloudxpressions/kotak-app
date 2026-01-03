<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEducation extends Model
{
    use HasFactory;

    protected $table = 'admin_education';

    protected $fillable = [
        'admin_id',
        'qualification',
        'year_of_passing',
        'medium',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
