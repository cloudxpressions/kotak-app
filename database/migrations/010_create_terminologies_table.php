<?php

// Migration to create the terminologies table
$sql = "
CREATE TABLE IF NOT EXISTS terminologies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";