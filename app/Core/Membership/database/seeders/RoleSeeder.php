<?php

namespace App\Core\Membership\database\seeders;

use App\Core\Membership\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->insert([
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'Moderator', 'slug' => 'moderator'],
            ['name' => 'MSME User', 'slug' => 'msme_user'],
            ['name' => 'Instructor', 'slug' => 'instructor'],
            ['name' => 'Student', 'slug' => 'student'],
        ]);
    }
}
