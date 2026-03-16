<?php

namespace Database\Seeders;

use App\Core\Membership\Enum\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(
        User $user,
    ): void
    {
        $user->profile()->truncate();
        $user->truncate();

        $usersList = [
            [
                'user' => [
                    'name' => 'Admin User',
                    'email' => 'admin@test.com',
                    'password' => Hash::make('password'),
                    'role' => UserRole::ADMIN,
                ],
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
                    'role' => UserRole::MODERATOR,
                ],
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
                    'role' => UserRole::MSME_USER,
                ],
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
            $user->updateOrCreate(
                ['email' => $userItem['user']['email']],
                $userItem['user']
            )->profile()->updateOrCreate(
                ["user_id" => $user->id],
                $userItem['profile']
            );
        }
    }
}
