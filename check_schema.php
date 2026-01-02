<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$db_config = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']}",
        $db_config['username'],
        $db_config['password'],
        $db_config['options']
    );
    
    $tables = ['languages', 'users', 'topics'];
    foreach ($tables as $table) {
        echo "[$table]\n";
        try {
            $stmt = $pdo->prepare("SHOW CREATE TABLE `$table` ");
            $stmt->execute();
            echo $stmt->fetch(PDO::FETCH_ASSOC)['Create Table'] . "\n\n";
        } catch (Exception $e) { echo "Table $table not found.\n\n"; }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
