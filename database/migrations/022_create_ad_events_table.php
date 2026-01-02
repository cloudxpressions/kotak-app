<?php

// Migration to create the ad_events table
$sql = "
CREATE TABLE IF NOT EXISTS ad_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ad_type ENUM('banner', 'interstitial', 'rewarded') NOT NULL,
    event ENUM('shown', 'clicked') NOT NULL,
    platform VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
";