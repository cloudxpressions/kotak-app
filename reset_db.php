<?php

// Database reset script

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load database configuration
$config = require __DIR__ . '/config/database.php';

try {
    $host = $config['host'];
    $port = $config['port'];
    $dbname = $config['dbname'];
    $username = $config['username'];
    $password = $config['password'];
    $charset = $config['charset'];

    $dsn = "mysql:host={$host};port={$port};charset={$charset}";

    // Connect without specifying database to drop and recreate it
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Drop and recreate database
    $pdo->exec("DROP DATABASE IF EXISTS `{$dbname}`");
    $pdo->exec("CREATE DATABASE `{$dbname}` CHARACTER SET {$charset}");
    $pdo->exec("USE `{$dbname}`");

    echo "Database {$dbname} recreated successfully!\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}