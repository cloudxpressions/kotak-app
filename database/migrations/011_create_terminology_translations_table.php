<?php

// Migration to create the terminology_translations table
$sql = "
CREATE TABLE IF NOT EXISTS terminology_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    terminology_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    term VARCHAR(255) NOT NULL,
    definition TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (terminology_id) REFERENCES terminologies(id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(code),
    UNIQUE KEY unique_terminology_language (terminology_id, language_code)
);
";