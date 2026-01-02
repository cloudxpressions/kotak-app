<?php

// Migration to create the languages table
$sql = "
CREATE TABLE IF NOT EXISTS languages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(10) UNIQUE NOT NULL,
    name VARCHAR(50) NOT NULL,
    native_name VARCHAR(50),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

// Insert default languages
$insertSql = "
INSERT INTO languages (code, name, native_name, is_active) VALUES
('en', 'English', 'English', 1),
('ta', 'Tamil', 'தமிழ்', 1),
('hi', 'Hindi', 'हिंदी', 1)
ON DUPLICATE KEY UPDATE name=name;
";