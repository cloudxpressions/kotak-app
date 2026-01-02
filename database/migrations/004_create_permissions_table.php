<?php

// Migration to create the permissions table
$sql = "
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

// Insert default permissions
$insertSql = "
INSERT INTO permissions (name, description) VALUES
('view_dashboard', 'Can view dashboard'),
('manage_users', 'Can manage users'),
('manage_content', 'Can manage content'),
('manage_settings', 'Can manage settings'),
('view_analytics', 'Can view analytics'),
('access_content', 'Can access content'),
('take_tests', 'Can take tests'),
('view_profile', 'Can view profile'),
('save_items', 'Can save items')
ON DUPLICATE KEY UPDATE name=name;
";