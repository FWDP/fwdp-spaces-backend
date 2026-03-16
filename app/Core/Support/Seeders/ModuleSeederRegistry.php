<?php

namespace App\Core\Support\Seeders;

use App\Core\Membership\database\seeders\MembershipModuleSeeder;

class ModuleSeederRegistry
{
    public static function get(): array
    {
        return [
            MembershipModuleSeeder::class,
        ];
    }
}
