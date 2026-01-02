<?php

namespace App\Core;

class Request
{
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function uri()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function input($key = null, $default = null)
    {
        $input = self::getJsonInput();
        
        if ($key === null) {
            return $input;
        }
        
        return $input[$key] ?? $default;
    }

    public static function getJsonInput()
    {
        static $json = null;
        
        if ($json === null) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $json = $_POST;
            }
        }
        
        return $json ?: [];
    }

    public static function header($key, $default = null)
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$headerKey] ?? $default;
    }

    public static function bearerToken()
    {
        $authHeader = self::header('Authorization');
        
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            return substr($authHeader, 7);
        }
        
        return null;
    }

    public static function preferredLanguage()
    {
        $languageHeader = self::header('Accept-Language');
        return $languageHeader ? substr($languageHeader, 0, 2) : 'en';
    }
}