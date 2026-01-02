<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Validator;
use App\Services\AuthService;

class AuthController
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register($params = [])
    {
        $data = Request::input();
        
        $result = $this->authService->register($data);
        
        if ($result['success']) {
            Response::success([
                'user' => $result['user'],
                'token' => $result['token']
            ], 'Registration successful');
        } else {
            Response::error($result['message'] ?? 'Registration failed', 400, $result['errors'] ?? null);
        }
    }

    public function login($params = [])
    {
        $data = Request::input();
        
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        
        if (!$email || !$password) {
            Response::error('Email and password are required', 400);
        }
        
        $result = $this->authService->login($email, $password);
        
        if ($result['success']) {
            Response::success([
                'user' => $result['user'],
                'token' => $result['token']
            ], 'Login successful');
        } else {
            Response::error($result['message'], 401);
        }
    }

    public function logout($params = [])
    {
        $result = $this->authService->logout();
        
        if ($result) {
            Response::success(null, 'Logout successful');
        } else {
            Response::error('Logout failed', 400);
        }
    }

    public function refresh($params = [])
    {
        // In a JWT system, refresh would typically involve validating a refresh token
        // and issuing a new access token
        Response::error('Token refresh not implemented', 501);
    }
}