<?php

// Migration to create the users table
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mobile VARCHAR(20) UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other'),
    dob DATE,
    qualification VARCHAR(100),
    occupation VARCHAR(100),
    state VARCHAR(100),
    district VARCHAR(100),
    exam_target VARCHAR(50),
    preferred_language VARCHAR(10) DEFAULT 'en',
    device_id VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL,
    FOREIGN KEY (preferred_language) REFERENCES languages(code)
);
";