<?php

// Migration to create the material_translations table
$sql = "
CREATE TABLE IF NOT EXISTS material_translations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL,
    language_code VARCHAR(10) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    FOREIGN KEY (language_code) REFERENCES languages(code),
    UNIQUE KEY unique_material_language (material_id, language_code)
);
";