<?php

namespace App\Core\Profile\Http\Controllers;

use App\Core\Profile\Models\UserProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): Request
    {
        $profile = $request->user()->profile;

        $profile->avatar_url = $profile->avatar_path;

        return $profile;
    }

    public function update(
        Request $request,
    ): Request
    {
        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'avatar_url' => 'nullable|url'
        ]);
        return $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );
    }

    function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profile = $request->user()->profile;

        $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB
        ]);

        // delete old avatar if exists
        if ($profile->avatar_path) {
            Storage::disk('public')->delete($profile->avatar_path);
        }

        $path = $request->file('avatar')->store(
            "avatars/{$request->user()->id}",
            'public'
        );

        $profile->update([
            'avatar_path' => $path,
        ]);

        return response()->json([
            'avatar' => asset("storage/{$path}")
        ]);
    }
}
