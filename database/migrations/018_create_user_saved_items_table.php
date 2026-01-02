<?php

// Migration to create the user_saved_items table
$sql = "
CREATE TABLE IF NOT EXISTS user_saved_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entity_type ENUM('chapter', 'topic', 'question', 'terminology', 'material') NOT NULL,
    entity_id INT NOT NULL,
    action ENUM('bookmark', 'pin') DEFAULT 'bookmark',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_entity (user_id, entity_type, entity_id),
    UNIQUE KEY unique_user_entity_action (user_id, entity_type, entity_id, action)
);
";