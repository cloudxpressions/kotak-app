<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions with groups
        $permissions = [
            // User Management
            ['name' => 'user.view', 'group_name' => 'User Management'],
            ['name' => 'user.create', 'group_name' => 'User Management'],
            ['name' => 'user.update', 'group_name' => 'User Management'],
            ['name' => 'user.delete', 'group_name' => 'User Management'],
            
            // Role Management
            ['name' => 'role.view', 'group_name' => 'Role Management'],
            ['name' => 'role.create', 'group_name' => 'Role Management'],
            ['name' => 'role.update', 'group_name' => 'Role Management'],
            ['name' => 'role.delete', 'group_name' => 'Role Management'],
            
            // Language Management
            ['name' => 'language.view', 'group_name' => 'Language Management'],
            ['name' => 'language.create', 'group_name' => 'Language Management'],
            ['name' => 'language.update', 'group_name' => 'Language Management'],
            ['name' => 'language.delete', 'group_name' => 'Language Management'],
            
            // System Settings
            ['name' => 'settings.view', 'group_name' => 'System Settings'],
            ['name' => 'settings.update', 'group_name' => 'System Settings'],
            
            // Content Management
            ['name' => 'content.view', 'group_name' => 'Content Management'],
            ['name' => 'content.create', 'group_name' => 'Content Management'],
            ['name' => 'content.update', 'group_name' => 'Content Management'],
            ['name' => 'content.delete', 'group_name' => 'Content Management'],

            // Settings Management
            ['name' => 'settings.view', 'group_name' => 'Settings Management'],
            ['name' => 'settings.update', 'group_name' => 'Settings Management'],

            // Recaptcha Management
            ['name' => 'recaptcha.view', 'group_name' => 'Recaptcha Management'],
            ['name' => 'recaptcha.update', 'group_name' => 'Recaptcha Management'],

            // Database Backup Management
            ['name' => 'backup.view', 'group_name' => 'Database Backup Management'],
            ['name' => 'backup.create', 'group_name' => 'Database Backup Management'],
            ['name' => 'backup.delete', 'group_name' => 'Database Backup Management'],
            ['name' => 'backup.download', 'group_name' => 'Database Backup Management'],

            // Settings Management
            ['name' => 'settings.view', 'group_name' => 'Settings Management'],
            ['name' => 'settings.update', 'group_name' => 'Settings Management'],

            // Email Settings Management
            ['name' => 'email-settings.view', 'group_name' => 'Email Settings Management'],
            ['name' => 'email-settings.update', 'group_name' => 'Email Settings Management'],
            ['name' => 'email-settings.test', 'group_name' => 'Email Settings Management'],

            // Newsletter Management
            ['name' => 'newsletter.view', 'group_name' => 'Newsletter Management'],
            ['name' => 'newsletter.create', 'group_name' => 'Newsletter Management'],
            ['name' => 'newsletter.update', 'group_name' => 'Newsletter Management'],
            ['name' => 'newsletter.delete', 'group_name' => 'Newsletter Management'],

            // Audit Logs Management
            ['name' => 'audit.view', 'group_name' => 'Audit Logs Management'],
            ['name' => 'audit.delete', 'group_name' => 'Audit Logs Management'],

            // Legal Pages Management
            ['name' => 'legal_pages.view', 'group_name' => 'Legal Pages Management'],
            ['name' => 'legal_pages.create', 'group_name' => 'Legal Pages Management'],
            ['name' => 'legal_pages.update', 'group_name' => 'Legal Pages Management'],
            ['name' => 'legal_pages.delete', 'group_name' => 'Legal Pages Management'],

            // Testimonials Management
            ['name' => 'testimonials.view', 'group_name' => 'Testimonials Management'],
            ['name' => 'testimonials.create', 'group_name' => 'Testimonials Management'],
            ['name' => 'testimonials.update', 'group_name' => 'Testimonials Management'],
            ['name' => 'testimonials.delete', 'group_name' => 'Testimonials Management'],

            // FAQ Management
            ['name' => 'faq.view', 'group_name' => 'FAQ Management'],
            ['name' => 'faq.create', 'group_name' => 'FAQ Management'],
            ['name' => 'faq.update', 'group_name' => 'FAQ Management'],
            ['name' => 'faq.delete', 'group_name' => 'FAQ Management'],

            // Insurance Category Management
            ['name' => 'insurance_category.view', 'group_name' => 'Insurance Management'],
            ['name' => 'insurance_category.create', 'group_name' => 'Insurance Management'],
            ['name' => 'insurance_category.update', 'group_name' => 'Insurance Management'],
            ['name' => 'insurance_category.delete', 'group_name' => 'Insurance Management'],

            // Exam Management
            ['name' => 'exam.view', 'group_name' => 'Insurance Management'],
            ['name' => 'exam.create', 'group_name' => 'Insurance Management'],
            ['name' => 'exam.update', 'group_name' => 'Insurance Management'],
            ['name' => 'exam.delete', 'group_name' => 'Insurance Management'],

            // Chapter Management
            ['name' => 'chapter.view', 'group_name' => 'Insurance Management'],
            ['name' => 'chapter.create', 'group_name' => 'Insurance Management'],
            ['name' => 'chapter.update', 'group_name' => 'Insurance Management'],
            ['name' => 'chapter.delete', 'group_name' => 'Insurance Management'],

            // Concept Management
            ['name' => 'concept.view', 'group_name' => 'Insurance Management'],
            ['name' => 'concept.create', 'group_name' => 'Insurance Management'],
            ['name' => 'concept.update', 'group_name' => 'Insurance Management'],
            ['name' => 'concept.delete', 'group_name' => 'Insurance Management'],

            // One Liner Management
            ['name' => 'one_liner.view', 'group_name' => 'Insurance Management'],
            ['name' => 'one_liner.create', 'group_name' => 'Insurance Management'],
            ['name' => 'one_liner.update', 'group_name' => 'Insurance Management'],
            ['name' => 'one_liner.delete', 'group_name' => 'Insurance Management'],

            // Short & Simple Management
            ['name' => 'short_simple.view', 'group_name' => 'Insurance Management'],
            ['name' => 'short_simple.create', 'group_name' => 'Insurance Management'],
            ['name' => 'short_simple.update', 'group_name' => 'Insurance Management'],
            ['name' => 'short_simple.delete', 'group_name' => 'Insurance Management'],

            // Terminology Management
            ['name' => 'terminology.view', 'group_name' => 'Insurance Management'],
            ['name' => 'terminology.create', 'group_name' => 'Insurance Management'],
            ['name' => 'terminology.update', 'group_name' => 'Insurance Management'],
            ['name' => 'terminology.delete', 'group_name' => 'Insurance Management'],

            // Material Management
            ['name' => 'material.view', 'group_name' => 'Insurance Management'],
            ['name' => 'material.create', 'group_name' => 'Insurance Management'],
            ['name' => 'material.update', 'group_name' => 'Insurance Management'],
            ['name' => 'material.delete', 'group_name' => 'Insurance Management'],

            // Test Management
            ['name' => 'test.view', 'group_name' => 'Insurance Management'],
            ['name' => 'test.create', 'group_name' => 'Insurance Management'],
            ['name' => 'test.update', 'group_name' => 'Insurance Management'],
            ['name' => 'test.delete', 'group_name' => 'Insurance Management'],

            // Question Management
            ['name' => 'question.view', 'group_name' => 'Insurance Management'],
            ['name' => 'question.create', 'group_name' => 'Insurance Management'],
            ['name' => 'question.update', 'group_name' => 'Insurance Management'],
            ['name' => 'question.delete', 'group_name' => 'Insurance Management'],

            // Test Attempt Management
            ['name' => 'test_attempt.view', 'group_name' => 'Insurance Management'],
            ['name' => 'test_attempt.delete', 'group_name' => 'Insurance Management'],

            // Performance Stat Management
            ['name' => 'performance_stat.view', 'group_name' => 'Insurance Management'],
            ['name' => 'performance_stat.update', 'group_name' => 'Insurance Management'],
            ['name' => 'performance_stat.delete', 'group_name' => 'Insurance Management'],

            // User Saved Item Management
            ['name' => 'user_saved_item.view', 'group_name' => 'Insurance Management'],
            ['name' => 'user_saved_item.delete', 'group_name' => 'Insurance Management'],

        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'admin'],
                ['group_name' => $permission['group_name']]
            );
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'admin'
        ]);

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // Assign limited permissions to Manager
        $managerPermissions = [
            'user.view',
            'language.view',
            'content.view',
            'content.create',
            'content.update',
            'settings.view',
            'settings.update',
        ];
        $managerRole->syncPermissions($managerPermissions);
    }
}
