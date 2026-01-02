<?php

// Migration to create the question_translations table
$sql = "
CREATE TABLE IF NOT EXISTS question_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    question_text TEXT NOT NULL,
    option_a TEXT,
    option_b TEXT,
    option_c TEXT,
    option_d TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(code),
    UNIQUE KEY unique_question_language (question_id, language_code)
);
";