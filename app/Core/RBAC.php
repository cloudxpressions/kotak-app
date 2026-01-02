<?php

namespace App\Core;

use App\Models\User;

class RBAC
{
    public static function hasRole($user, $role)
    {
        if (!$user) {
            return false;
        }

        // In a simple implementation, we might just check a role field
        // In a more complex system, we'd check user roles from a roles table
        return $user->role === $role;
    }

    public static function hasPermission($user, $permission)
    {
        if (!$user) {
            return false;
        }

        // Check if user has the required permission
        // This would typically involve checking a permissions table
        // For now, we'll implement a simple role-based check
        $rolePermissions = self::getRolePermissions($user->role);
        return in_array($permission, $rolePermissions);
    }

    private static function getRolePermissions($role)
    {
        // Define permissions for each role
        $permissions = [
            'admin' => [
                'view_dashboard',
                'manage_users',
                'manage_content',
                'manage_settings',
                'view_analytics'
            ],
            'user' => [
                'access_content',
                'take_tests',
                'view_profile',
                'save_items'
            ]
        ];

        return $permissions[$role] ?? [];
    }

    public static function can($user, $permission)
    {
        return self::hasPermission($user, $permission);
    }
}