<?php

namespace App\Core\Profile\Services;

use App\Core\Profile\Models\UserProfile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function getProfile(User $user): ?UserProfile
    {
        return $user->profile;
    }
    public function updateProfile(User $user, array $data): UserProfile
    {
        $profile = $this->getProfile($user);

        if (!$profile) $profile = UserProfile::create(['user_id' => $user->id]);

        $profile->update([
            'first_name' => $data['first_name'] ?? $profile->first_name,
            'last_name' => $data['last_name'] ?? $profile->last_name,
            'gender' => $data['gender'] ?? $profile->gender,
            'phone' => $data['phone'] ?? $profile->phone,
            'avatar_url' => $data['avatar'] ?? $profile->avatar_url,
        ]);

        return $profile->refresh();
    }

    public function uploadAvatar(User $user, $file): UserProfile
    {
        $profile = $this->getProfile($user);

        if (!$profile) $profile = UserProfile::create(['user_id' => $user->id]);

        if ($profile->avatar_path) Storage::disk('public')->delete($profile->avatar_path);

        $profile->update([
            'avatar_path' => $file->store("avatars/{$user->id}", 'public'),
        ]);

        return $profile->refresh();
    }
}