<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_education';

    protected $fillable = [
        'user_id',
        'qualification',
        'year_of_passing',
        'medium',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
