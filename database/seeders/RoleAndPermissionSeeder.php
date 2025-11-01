<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for all resources
        $permissions = [
            // Ticket permissions
            'ticket_view_any',
            'ticket_view',
            'ticket_create',
            'ticket_update',
            'ticket_delete',
            'ticket_view_own', // Custom permission for users to view their own tickets

            // Category permissions
            'category_view_any',
            'category_view',
            'category_create',
            'category_update',
            'category_delete',

            // Status permissions
            'status_view_any',
            'status_view',
            'status_create',
            'status_update',
            'status_delete',

            // Comment permissions
            'comment_view_any',
            'comment_view',
            'comment_create',
            'comment_update',
            'comment_delete',
            'comment_view_own', // Custom permission for users to view their own comments

            // User management permissions
            'user_view_any',
            'user_view',
            'user_create',
            'user_update',
            'user_delete',

            // Department permissions
            'department_view_any',
            'department_view',
            'department_create',
            'department_update',
            'department_delete',

            // Role and permission management
            'role_view_any',
            'role_view',
            'role_create',
            'role_update',
            'role_delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $agentRole = Role::firstOrCreate(['name' => 'agent']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign permissions to roles

        // Super Admin role - all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin role - all permissions except role management
        $adminRole->givePermissionTo(Permission::whereNotIn('name', ['role_create', 'role_update', 'role_delete'])->get());

        // Agent role - can manage all resources except users, roles, and departments
        $agentRole->givePermissionTo([
            'ticket_view_any', 'ticket_view', 'ticket_create', 'ticket_update',
            'category_view_any', 'category_view', 'category_create', 'category_update',
            'status_view_any', 'status_view', 'status_create', 'status_update',
            'comment_view_any', 'comment_view', 'comment_create', 'comment_update',
            'department_view_any', 'department_view',
        ]);

        // User role - limited permissions
        $userRole->givePermissionTo([
            'ticket_view_own', 'ticket_create',
            'comment_view_own', 'comment_create',
            'category_view_any', 'category_view',
            'status_view_any', 'status_view',
        ]);

        // Create a default super admin user
        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Create a default admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Create a default agent user
        $agent = User::firstOrCreate([
            'email' => 'agent@example.com',
        ], [
            'name' => 'Support Agent',
            'password' => bcrypt('password'),
        ]);
        $agent->assignRole('agent');

        // Create a default regular user
        $regularUser = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Regular User',
            'password' => bcrypt('password'),
        ]);
        $regularUser->assignRole('user');
    }
}