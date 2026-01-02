<?php

namespace App\Controllers\Api;

use App\Core\Request;
use App\Core\Response;
use App\Core\Auth;

class ProfileController
{
    public function show($params = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            Response::unauthorized('Authentication required');
        }
        
        // Exclude sensitive information from the response
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'qualification' => $user->qualification,
            'occupation' => $user->occupation,
            'state' => $user->state,
            'district' => $user->district,
            'exam_target' => $user->exam_target,
            'preferred_language' => $user->preferred_language,
            'created_at' => $user->created_at,
            'last_login_at' => $user->last_login_at
        ];
        
        Response::success($userData, 'Profile retrieved successfully');
    }

    public function update($params = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            Response::unauthorized('Authentication required');
        }
        
        $data = Request::input();
        
        // Update allowed fields
        $allowedFields = [
            'name', 'mobile', 'gender', 'dob', 'qualification', 
            'occupation', 'state', 'district', 'exam_target', 'preferred_language'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $user->$field = $data[$field];
            }
        }
        
        if ($user->save()) {
            Response::success([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'gender' => $user->gender,
                'dob' => $user->dob,
                'qualification' => $user->qualification,
                'occupation' => $user->occupation,
                'state' => $user->state,
                'district' => $user->district,
                'exam_target' => $user->exam_target,
                'preferred_language' => $user->preferred_language,
                'last_login_at' => $user->last_login_at
            ], 'Profile updated successfully');
        } else {
            Response::error('Failed to update profile', 500);
        }
    }
}