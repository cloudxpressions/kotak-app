<?php

namespace App\Core;

use PDO;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        
        $host = $config['host'];
        $port = $config['port'];
        $dbname = $config['dbname'];
        $username = $config['username'];
        $password = $config['password'];
        $charset = $config['charset'];
        $options = $config['options'];

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

        try {
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function __clone()
    {
        throw new \Exception('Cloning is not allowed');
    }

    public function __wakeup()
    {
        throw new \Exception('Unserializing is not allowed');
    }
}