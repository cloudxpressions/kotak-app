<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$db = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO("mysql:host={$db['host']};dbname={$db['dbname']}", $db['username'], $db['password'], $db['options']);
    echo "Connected to Database.\n";
} catch (Exception $e) { die("DB Error: " . $e->getMessage()); }

// 1. Seed Languages (Required for Users FK)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE languages");
$pdo->prepare("INSERT INTO languages (code, name) VALUES (?, ?), (?, ?)")
    ->execute(['en', 'English', 'hi', 'Hindi']);
echo "Seeded Languages.\n";

// 2. Seed Users
$pdo->exec("TRUNCATE TABLE users");
$pdo->prepare("INSERT INTO users (name, email, password, mobile, exam_target, preferred_language, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)")
    ->execute(['Sudhanshu Admin', 'admin@insurancenguide.com', 'password', '9876543210', 'IC-38', 'en', 1]);
echo "Seeded Users.\n";

// 3. Seed Chapters
$pdo->exec("TRUNCATE TABLE chapters");
$pdo->exec("TRUNCATE TABLE chapter_translations");

// Insert chapter base record
$pdo->prepare("INSERT INTO chapters (order_no, is_active) VALUES (?, ?)")
    ->execute([1, 1]);

// Get the inserted chapter ID
$chapter_id = $pdo->lastInsertId();

// Insert translation record
$pdo->prepare("INSERT INTO chapter_translations (chapter_id, language_code, title, description) VALUES (?, ?, ?, ?)")
    ->execute([$chapter_id, 'en', 'Intro to Insurance', 'Basics of Risk']);
echo "Seeded Chapters.\n";

$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
echo "Done.\n";
