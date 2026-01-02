<?php

namespace App\Core;

class Router
{
    private static $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => []
    ];

    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post($uri, $action)
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function put($uri, $action)
    {
        self::$routes['PUT'][$uri] = $action;
    }

    public static function delete($uri, $action)
    {
        self::$routes['DELETE'][$uri] = $action;
    }

    public static function patch($uri, $action)
    {
        self::$routes['PATCH'][$uri] = $action;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path if needed
        $basePath = '/api'; // API calls start with /api
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        } else {
            // If not an API call, check if it's going to api.php directly
            $scriptName = $_SERVER['SCRIPT_NAME'];
            if (strpos($scriptName, 'api.php') !== false) {
                // The base path is already handled by the script
            } else {
                // Not an API request, could be admin panel
                return;
            }
        }

        // Remove trailing slash
        $uri = rtrim($uri, '/');

        // Check if route exists
        if (isset(self::$routes[$method][$uri])) {
            $action = self::$routes[$method][$uri];
            $this->executeAction($action);
            return;
        }

        // Check for routes with parameters
        foreach (self::$routes[$method] as $route => $action) {
            if (strpos($route, '{') !== false) { // Route with parameters
                $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove full match

                    // Extract parameter names
                    preg_match_all('/\{(\w+)\}/', $route, $paramNames);
                    $paramNames = $paramNames[1];

                    // Combine parameter names with values
                    $routeParams = array_combine($paramNames, $matches);

                    // Call the action with parameters
                    $this->executeAction($action, $routeParams);
                    return;
                }
            }
        }

        // Route not found
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    private function executeAction($action, $params = [])
    {
        try {
            if (is_callable($action)) {
                call_user_func($action, $params);
            } elseif (is_array($action) && count($action) === 2) {
                list($controller, $method) = $action;
                
                // Handle namespaced controllers
                if (is_string($controller)) {
                    $controller = new $controller();
                }
                
                call_user_func_array([$controller, $method], [$params]);
            } else {
                throw new \Exception('Invalid action');
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}