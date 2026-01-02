<?php

// Migration to create the topic_translations table
$sql = "
CREATE TABLE IF NOT EXISTS topic_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content_html TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(code),
    UNIQUE KEY unique_topic_language (topic_id, language_code)
);
";