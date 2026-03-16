<?php

namespace App\Core\Subscriptions\database\seeders;

use App\Core\Subscriptions\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['name' => 'Free'],
            [
                'price' => 0,
                'duration_days' => 30,
                'features' => ['basic_courses']
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['name' => 'Premium'],
            [
                'price' => 499,
                'duration_days' => 365,
                'features' => ['all_courses','certificates']
            ]
        );
    }
}
