<?php

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Core\Auth;
use App\Core\RBAC;

class RoleMiddleware
{
    public static function handle($requiredRole)
    {
        $user = Auth::user();

        if (!$user) {
            Response::unauthorized('Authentication required');
        }

        if (!RBAC::hasRole($user, $requiredRole)) {
            Response::forbidden('Insufficient permissions');
        }

        // User has required role, continue with request
        return true;
    }
}