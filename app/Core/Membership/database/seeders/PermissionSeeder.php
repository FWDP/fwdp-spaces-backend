<?php

namespace App\Core\Membership\database\seeders;

use App\Core\Membership\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::query()->insert([
            ['name' => 'Manage Users', 'slug' => 'manage_users'],
            ['name' => 'Manage Roles', 'slug' => 'manage_roles'],
            ['name' => 'View Analytics', 'slug' => 'view_analytics'],
            ['name' => 'Create Course', 'slug' => 'create_course'],
            ['name' => 'Update Course', 'slug' => 'update_course'],
            ['name' => 'Delete Course', 'slug' => 'delete_course'],
            ['name' => 'Publish Course', 'slug' => 'publish_course'],
        ]);
    }
}
