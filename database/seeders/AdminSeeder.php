<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        $managerRole = Role::where('name', 'Manager')->where('guard_name', 'admin')->first();

        // Create Super Admin User
        $superUser = Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if ($superAdminRole) {
            $superUser->syncRoles([$superAdminRole]);
        }

        // Create Manager User
        $managerUser = Admin::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if ($managerRole) {
            $managerUser->syncRoles([$managerRole]);
        }
    }
}
