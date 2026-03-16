<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstalledCoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'core_auth',
            'core_profile',
            'core_admin',
            'core_subscriptions',
            'core_payments',
        ];

        foreach ($modules as $module) {
            DB::table('modules')->insert([
                'name' => $module,
                'enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
