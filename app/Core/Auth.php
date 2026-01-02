<?php

namespace App\Core;

use App\Models\User;
use App\Services\JwtService;

class Auth
{
    public static function user()
    {
        $token = Request::bearerToken();

        if (!$token) {
            return null;
        }

        try {
            $payload = JwtService::verifyToken($token);
            $userId = $payload->user_id ?? null;

            if ($userId) {
                return User::find($userId);
            }
        } catch (\Exception $e) {
            // Token is invalid
            return null;
        }

        return null;
    }

    public static function id()
    {
        $user = self::user();
        return $user ? $user->id : null;
    }

    public static function check()
    {
        return self::user() !== null;
    }

    public static function login($user, $remember = false)
    {
        $token = JwtService::generateToken($user->id, $user->email);
        return $token;
    }

    public static function logout()
    {
        // In a stateless JWT system, we don't store tokens server-side
        // The client should remove the token from local storage
        return true;
    }
}