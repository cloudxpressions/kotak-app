<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'skill_name',
        'proficiency_level',
        'description',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
