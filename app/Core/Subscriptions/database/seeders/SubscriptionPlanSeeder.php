<?php

namespace App\Core\Subscriptions\database\seeders;

use App\Core\Subscriptions\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPlan::updateOrCreate(
            ['code' => 'FREE'],
            [
                'name' => 'Free Plan',
                'price' => 0,
                'trial_days' => 30,
                'features' => ['basic_courses'],
            ]
        );

        SubscriptionPlan::updateOrCreate(
            ['code' => 'PRO'],
            [
                'name' => 'Premium Plan',
                'price' => 499,
                'trial_days' => 365,
                'features' => ['all_courses', 'certificates'],
            ]
        );
    }
}
