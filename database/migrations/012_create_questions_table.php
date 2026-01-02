<?php

// Migration to create the questions table
$sql = "
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    difficulty ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    correct_option ENUM('a', 'b', 'c', 'd') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";