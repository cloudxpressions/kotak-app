<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdMobSetting extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('admob_setting')
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'app_id',
        'banner_id',  // Changed from banner_id to match migration: banner_id
        'interstitial_id',  // Changed from interstitial_id to match migration: interstitial_id
        'rewarded_id',  // Changed from rewarded_id to match migration: rewarded_id
        'native_id',  // Changed from native_id to match migration: native_id
        'is_live',  // Changed from is_live to match migration: is_live
    ];

    protected $casts = [
        'is_live' => 'boolean',
    ];
}