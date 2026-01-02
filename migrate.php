<?php

// Simple migration runner script

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

    // Connect without specifying database to create it if needed
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET {$charset}");
    $pdo->exec("USE `{$dbname}`");

    echo "Connected to database: {$dbname}\n";

    // Get all migration files
    $migrationDir = __DIR__ . '/database/migrations';
    $migrationFiles = glob($migrationDir . '/*.php');

    if (empty($migrationFiles)) {
        echo "No migration files found.\n";
        exit(0);
    }

    // Sort migration files by number prefix
    usort($migrationFiles, function($a, $b) {
        $numA = (int)basename($a, '.php');
        $numB = (int)basename($b, '.php');
        return $numA - $numB;
    });

    // Execute each migration
    foreach ($migrationFiles as $file) {
        echo "Executing migration: " . basename($file) . "\n";
        
        // Include the migration file to get the SQL
        include $file;
        
        // Execute the SQL if defined
        if (isset($sql)) {
            $pdo->exec($sql);
            echo "  - Executed SQL for " . basename($file) . "\n";
        }
        
        // Execute insert SQL if defined
        if (isset($insertSql)) {
            $pdo->exec($insertSql);
            echo "  - Executed insert SQL for " . basename($file) . "\n";
        }
    }

    echo "\nAll migrations completed successfully!\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
    exit(1);
}