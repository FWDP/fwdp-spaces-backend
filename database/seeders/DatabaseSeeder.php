<?php

namespace Database\Seeders;

use App\Core\Membership\database\seeders\RoleSeeder;
use App\Core\Membership\database\seeders\UserSeeder;
use App\Core\Subscriptions\database\seeders\SubscriptionPlanSeeder;
use App\Core\Support\Seeders\ModuleSeederRegistry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (ModuleSeederRegistry::get() as $module) {
            $this->call($module);
        }
    }
}
