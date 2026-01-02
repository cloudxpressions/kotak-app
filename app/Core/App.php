<?php

namespace App\Core;

class App
{
    private static $instance = null;
    private $container = [];

    private function __construct()
    {
        $this->loadEnvironment();
        $this->registerCoreServices();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(dirname(__DIR__)));
        $dotenv->safeLoad();
    }

    private function registerCoreServices()
    {
        // Register database
        $this->container['db'] = function () {
            return Database::getInstance();
        };

        // Register router
        $this->container['router'] = function () {
            return new Router();
        };

        // Register language handler
        $this->container['language'] = function () {
            return new Language();
        };
    }

    public function get($key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key]();
        }
        return null;
    }

    public function run()
    {
        // Load routes
        require_once dirname(__DIR__, 2) . '/routes/api.php';
        
        // Dispatch the router
        $this->get('router')->dispatch();
    }
}