<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile', 'whatsapp_number', 'image', 'dob', 'bio', 'short_bio', 'gender', 'is_differently_abled',
        'locality', 'address', 'pincode', 'aadhaar_number', 'document',
        'country_id', 'state_id', 'city_id',
        'fathers_name', 'mothers_name', 'parent_mobile_number',
        'language_id', 'dateformat_id', 'timezone_id', 'currency_id',
        'medium_of_exam', 'favorite_topics',
        'user_classifications_id', 'community_id', 'd_a_category_id', 'religion_id', 'special_category_id',
        'facebook', 'twitter', 'linkedin',
        'login_attempts', 'account_locked_until', 'account_locked_reason',
        'mobile_verified_at', 'last_profile_update_at', 'last_password_change_at', 'dark_mode_enabled',
        'referral', 'subscribe', 'payout', 'payout_icon', 'payout_email', 'special_commission',
        'provider', 'provider_id',
        'is_active', 'is_banned',
        'delete_request_at', 'delete_request_reason'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'favorite_topics' => 'array',
            'account_locked_until' => 'datetime',
            'mobile_verified_at' => 'datetime',
            'last_profile_update_at' => 'datetime',
            'last_password_change_at' => 'datetime',
            'delete_request_at' => 'datetime',
        ];
    }

    // Relationships
    public function country() { return $this->belongsTo(\App\Models\Country::class); }
    public function state() { return $this->belongsTo(\App\Models\State::class); }
    public function city() { return $this->belongsTo(\App\Models\City::class); }
    public function language() { return $this->belongsTo(\App\Models\Language::class); }
    public function dateFormat() { return $this->belongsTo(\App\Models\DateFormat::class, 'dateformat_id'); }
    public function timeZone() { return $this->belongsTo(\App\Models\TimeZone::class, 'timezone_id'); }
    public function currency() { return $this->belongsTo(\App\Models\Currency::class); }
    public function userClassification() { return $this->belongsTo(\App\Models\UserClassification::class, 'user_classifications_id'); }
    public function community() { return $this->belongsTo(\App\Models\Community::class); }
    public function daCategory() { return $this->belongsTo(\App\Models\DACategory::class, 'd_a_category_id'); }
    public function religion() { return $this->belongsTo(\App\Models\Religion::class); }
    public function specialCategory() { return $this->belongsTo(\App\Models\SpecialCategory::class); }
    
    // Education and Skills
    public function education() { return $this->hasMany(\App\Models\UserEducation::class); }
    public function skills() { return $this->hasMany(\App\Models\UserSkill::class); }
}
