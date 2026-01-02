<?php

return [
    'enabled' => filter_var($_ENV['ADS_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
    'interstitial_interval' => (int)($_ENV['INTERSTITIAL_INTERVAL'] ?? 3),
    'rewarded_enabled' => true,
    'banner_enabled' => true,
];