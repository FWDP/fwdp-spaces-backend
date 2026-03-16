<?php

namespace App\Core\Membership\database\seeders;

use Illuminate\Database\Seeder;

class MembershipModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
