<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function getSettings($publicOnly = true)
    {
        return Setting::getAll($publicOnly);
    }

    public function getSetting($group, $key)
    {
        return Setting::get($group, $key);
    }

    public function updateSetting($group, $key, $value, $valueType = 'string', $isPublic = false)
    {
        $setting = Setting::findByGroupAndKey($group, $key);
        
        if (!$setting) {
            $setting = new Setting();
            $setting->group_name = $group;
            $setting->key_name = $key;
        }
        
        $setting->value = $value;
        $setting->value_type = $valueType;
        $setting->is_public = $isPublic;
        $setting->updated_at = date('Y-m-d H:i:s');
        
        return $setting->save();
    }

    public function getExamSettings()
    {
        return [
            'negative_marking' => $this->getSetting('exam', 'negative_marking') ?? false,
            'time_limit' => $this->getSetting('exam', 'time_limit') ?? null,
            'pass_percentage' => $this->getSetting('exam', 'pass_percentage') ?? 50
        ];
    }

    public function getAdSettings()
    {
        return [
            'enabled' => $this->getSetting('ads', 'enabled') ?? true,
            'interstitial_interval' => $this->getSetting('ads', 'interstitial_interval') ?? 3
        ];
    }

    public function getGeneralSettings()
    {
        return [
            'app_version' => $this->getSetting('general', 'app_version') ?? '1.0.0',
            'maintenance_mode' => $this->getSetting('general', 'maintenance_mode') ?? false
        ];
    }
}