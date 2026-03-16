<?php

namespace App\Core\Membership\database\seeders;

use App\Core\Membership\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('user_profiles')->truncate();

        $usersList = [
            [
                'user' => [
                    'name' => 'Admin User',
                    'email' => 'admin@test.com',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ],
                'role' => UserRole::ADMIN,
                'profile' => [
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'email' => 'admin@test.com',
                    'phone' => '09170000001',
                    'avatar_url' => null,
                    'avatar_path' => null,
                    'gender' => 'male',
                ],
            ],
            [
                'user' => [
                    'name' => 'Moderator User',
                    'email' => 'moderator@test.com',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ],
                'role' => UserRole::MODERATOR,
                'profile' => [
                    'first_name' => 'Moderator',
                    'last_name' => 'User',
                    'email' => 'moderator@test.com',
                    'phone' => '09170000002',
                    'avatar_url' => null,
                    'avatar_path' => null,
                    'gender' => 'male',
                ],
            ],
            [
                'user' => [
                    'name' => 'MSME User',
                    'email' => 'msme@test.com',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ],
                'role' => UserRole::MSME_USER,
                'profile' => [
                    'first_name' => 'MSME',
                    'last_name' => 'User',
                    'email' => 'msme@test.com',
                    'phone' => '09170000003',
                    'avatar_url' => null,
                    'avatar_path' => null,
                    'gender' => 'female',
                ],
            ],
        ];

        foreach ($usersList as $userItem) {
            $user = User::query()->updateOrCreate(
                ['email' => $userItem['user']['email']],
                $userItem['user']
            );

            $user->assignRole($userItem['role']);

            $user->profile()->updateOrCreate($userItem['profile']);
        }
    }
}
