<?php

namespace Database\Seeders;

use BalajiDharma\LaravelCategory\Models\CategoryType;
use BalajiDharma\LaravelMenu\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminCoreSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'admin user', 'permission list', 'permission create', 'permission edit', 'permission delete',
            'role list', 'role create', 'role edit', 'role delete',
            'user list', 'user create', 'user edit', 'user delete',
            'menu list', 'menu create', 'menu edit', 'menu delete',
            'menu.item list', 'menu.item create', 'menu.item edit', 'menu.item delete',
            'category list', 'category create', 'category edit', 'category delete',
            'category.type list', 'category.type create', 'category.type edit', 'category.type delete',
            'media list', 'media create', 'media edit', 'media delete',
            'comment list', 'comment create', 'comment edit', 'comment delete',
            'thread list', 'thread create', 'thread edit', 'thread delete',
            'activitylog list', 'activitylog delete',
            'attribute list', 'attribute create', 'attribute edit', 'attribute delete',
            'reaction list', 'reaction create', 'reaction edit', 'reaction delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleWriter = Role::firstOrCreate(['name' => 'writer']);

        // Assign permissions
        foreach ($permissions as $permission) {
            $roleAdmin->givePermissionTo($permission);
        }

        $roleWriter->givePermissionTo('admin user');
        foreach ($permissions as $permission) {
            if (Str::contains($permission, 'list')) {
                $roleWriter->givePermissionTo($permission);
            }
        }

        // Demo Users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role' => $roleSuperAdmin
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => $roleAdmin
            ],
            [
                'name' => 'Example User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role' => $roleWriter
            ],
        ];

        foreach ($users as $data) {
            $user = \App\Models\User::firstOrCreate(
                ['email' => $data['email']],
                array_merge(\App\Models\User::factory()->raw(), [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                ])
            );
            $user->assignRole($data['role']);
        }

        // Menu Creation
        $menu = Menu::firstOrCreate(
            ['machine_name' => 'admin'],
            ['name' => 'Admin', 'description' => 'Admin Menu']
        );

        $menu_items = [
            [
                'name' => 'Dashboard',
                'uri' => '/<admin>',
                'enabled' => 1,
                'weight' => 0,
                'icon' => 'M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z',
            ],
            [
                'name' => 'Permissions',
                'uri' => '/<admin>/permission',
                'enabled' => 1,
                'weight' => 1,
                'icon' => 'M12,12H19C18.47,16.11 15.72,19.78 12,20.92V12H5V6.3L12,3.19M12,1L3,5V11C3,16.55 6.84,21.73 12,23C17.16,21.73 21,16.55 21,11V5L12,1Z',
            ],
            // ... (rest of your menu items)
        ];

        foreach ($menu_items as $item) {
            $menu->menuItems()->updateOrCreate(['name' => $item['name']], $item);
        }

        // Category Types
        $category_types = [
            ['name' => 'Category', 'machine_name' => 'category', 'description' => 'Main Category'],
            ['name' => 'Tag', 'machine_name' => 'tag', 'description' => 'Site Tags', 'is_flat' => true],
            ['name' => 'Admin Tag', 'machine_name' => 'admin_tag', 'description' => 'Admin Tags', 'is_flat' => true],
            ['name' => 'Forum Category', 'machine_name' => 'forum_category', 'description' => 'Forum Category'],
            ['name' => 'Forum Tag', 'machine_name' => 'forum_tag', 'description' => 'Forum Tags', 'is_flat' => true],
        ];

        foreach ($category_types as $type) {
            CategoryType::updateOrCreate(['machine_name' => $type['machine_name']], $type);
        }

        // Default Forum Category
        $forumCategoryType = CategoryType::firstWhere('machine_name', 'forum_category');
        if ($forumCategoryType) {
            $forumCategoryType->categories()->updateOrCreate(
                ['name' => 'General'],
                ['description' => 'General Forum Category']
            );
        }
    }
}
