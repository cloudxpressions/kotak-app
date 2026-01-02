<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$db_config = require __DIR__ . '/config/database.php';
$pdo = new PDO("mysql:host={$db_config['host']};dbname={$db_config['dbname']}", $db_config['username'], $db_config['password']);
$s = $pdo->query("SHOW TABLES");
while($r=$s->fetch(PDO::FETCH_NUM)) echo $r[0]."\n";
