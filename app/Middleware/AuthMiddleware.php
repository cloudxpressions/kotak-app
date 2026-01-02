<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\JwtService;

class AuthMiddleware
{
    public static function handle()
    {
        $token = Request::bearerToken();

        if (!$token) {
            Response::unauthorized('Authorization token required');
        }

        try {
            $payload = JwtService::verifyToken($token);
            // Token is valid, continue with request
            return true;
        } catch (\Exception $e) {
            Response::unauthorized('Invalid or expired token');
        }
    }
}