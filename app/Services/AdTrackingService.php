<?php

namespace App\Services;

use App\Models\AdEvent;

class AdTrackingService
{
    public function trackAdEvent($userId, $adType, $event, $platform = null)
    {
        $adEvent = new AdEvent();
        $adEvent->user_id = $userId;
        $adEvent->ad_type = $adType;
        $adEvent->event = $event;
        $adEvent->platform = $platform ?? $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $adEvent->created_at = date('Y-m-d H:i:s');
        
        return $adEvent->save();
    }

    public function getAdPerformance($adType = null, $startDate = null, $endDate = null)
    {
        return AdEvent::getPerformance($adType, $startDate, $endDate);
    }

    public function getUserAdHistory($userId)
    {
        return AdEvent::findByUser($userId);
    }

    public function shouldShowInterstitial($userId)
    {
        // Get settings
        $settingsService = new SettingsService();
        $interval = $settingsService->getSetting('ads', 'interstitial_interval') ?? 3;
        
        // Count how many interstitials the user has seen recently
        $recentInterstitials = AdEvent::countRecentByUserAndType(
            $userId, 
            'interstitial', 
            30 * 60 // Last 30 minutes
        );
        
        // Show interstitial if user hasn't seen enough recently
        return $recentInterstitials < $interval;
    }
}