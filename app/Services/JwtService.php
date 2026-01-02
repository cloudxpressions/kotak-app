<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public static function generateToken($userId, $email)
    {
        $config = require dirname(__DIR__, 3) . '/config/jwt.php';
        $secret = $config['secret'];
        $algorithm = $config['algorithm'];
        $expiresIn = $config['expires_in']; // in minutes

        $payload = [
            'iss' => $_SERVER['HTTP_HOST'] ?? 'insurance-guide', // Issuer
            'sub' => $userId, // Subject
            'email' => $email,
            'iat' => time(), // Issued at
            'exp' => time() + ($expiresIn * 60) // Expiration time
        ];

        return JWT::encode($payload, $secret, $algorithm);
    }

    public static function verifyToken($token)
    {
        $config = require dirname(__DIR__, 3) . '/config/jwt.php';
        $secret = $config['secret'];
        $algorithm = $config['algorithm'];

        try {
            $decoded = JWT::decode($token, new Key($secret, $algorithm));
            return $decoded;
        } catch (\Exception $e) {
            throw new \Exception('Invalid token: ' . $e->getMessage());
        }
    }

    public static function refreshToken($refreshToken)
    {
        // In a real implementation, you would validate the refresh token
        // and generate a new access token
        // For this example, we'll just generate a new token
        // In practice, refresh tokens should be stored and validated
    }
}