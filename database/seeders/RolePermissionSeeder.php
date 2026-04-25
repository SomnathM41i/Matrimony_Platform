<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        Role::create(['name' => 'super_admin',          'display_name' => 'Super Administrator']);
        Role::create(['name' => 'admin',                'display_name' => 'Administrator']);
        Role::create(['name' => 'relationship_manager', 'display_name' => 'Relationship Manager']);
        Role::create(['name' => 'user',                 'display_name' => 'End User']);

        // Permissions
        $permissions = [
            ['manage_users',         'Manage Users',         'users'],
            ['manage_roles',         'Manage Roles',         'admin'],
            ['manage_matrimony',     'Manage Matrimony',     'matrimony'],
            ['manage_subscriptions', 'Manage Subscriptions', 'billing'],
            ['manage_rm',            'Manage Relationship Managers', 'admin'],
            ['manage_notifications', 'Send Notifications',   'comms'],
            ['manage_cms',           'Manage CMS',           'cms'],
            ['manage_seo',           'Manage SEO',           'cms'],
            ['manage_support',       'Handle Support',       'support'],
            ['view_analytics',       'View Analytics',       'reports'],
            ['manage_settings',      'Manage Settings',      'admin'],
            ['manage_lookups',       'Manage Lookup Data',   'admin'],
            ['view_logs',            'View Activity Logs',   'admin'],
        ];

        foreach ($permissions as [$name, $display, $group]) {
            Permission::create([
                'name' => $name,
                'display_name' => $display,
                'group' => $group
            ]);
        }
    }
}