<?php

namespace App\Services;

use App\Models\User;
use App\Core\Validator;
use App\Core\JwtService;
use App\Core\Language;

class AuthService
{
    public function register($data)
    {
        // Validate input
        $validator = new Validator();
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile' => 'required|unique:users,mobile'
        ];
        
        if (!$validator->validate($data, $rules)) {
            return ['success' => false, 'errors' => $validator->errors()];
        }

        // Create user
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'] ?? null;
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->gender = $data['gender'] ?? null;
        $user->dob = $data['dob'] ?? null;
        $user->qualification = $data['qualification'] ?? null;
        $user->occupation = $data['occupation'] ?? null;
        $user->state = $data['state'] ?? null;
        $user->district = $data['district'] ?? null;
        $user->exam_target = $data['exam_target'] ?? null;
        $user->preferred_language = $data['preferred_language'] ?? 'en';
        $user->device_id = $data['device_id'] ?? null;
        
        if ($user->save()) {
            $token = JwtService::generateToken($user->id, $user->email);
            return [
                'success' => true,
                'user' => $user,
                'token' => $token
            ];
        }
        
        return ['success' => false, 'message' => 'Registration failed'];
    }

    public function login($email, $password)
    {
        $user = User::findByEmail($email);
        
        if (!$user || !password_verify($password, $user->password)) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Update last login
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->save();

        $token = JwtService::generateToken($user->id, $user->email);
        return [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout()
    {
        // In JWT-based auth, we don't store tokens server-side
        // The client should remove the token from local storage
        return true;
    }
}