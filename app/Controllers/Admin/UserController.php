<?php

namespace App\Controllers\Admin;

use App\Core\Response;
use App\Core\Request;
use App\Models\User;

class UserController
{
    public function index()
    {
        if (ob_get_length()) ob_clean(); 
        $users = User::all(); 
        return Response::success($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Response::notFound('User not found');
        }
        return Response::success($user);
    }

    public function update($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Response::notFound('User not found');
        }

        $data = Request::all(); // Assuming Request::all() gets parsed JSON or POST data
        
        // Basic validation could go here
        
        $user->update($data);
        return Response::success($user, 'User updated successfully');
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Response::notFound('User not found');
        }

        $user->delete();
        return Response::success(null, 'User deleted successfully');
    }
    
    // Create method if needed (usually users register, but admin might create)
    public function store() {
        $data = Request::all();
        // validation...
        $user = User::create($data);
        return Response::success($user, 'User created successfully');
    }
}
