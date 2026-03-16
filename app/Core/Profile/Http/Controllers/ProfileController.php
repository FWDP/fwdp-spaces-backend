<?php

namespace App\Core\Profile\Http\Controllers;

use App\Core\Profile\Services\ProfileService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request, ProfileService $profileService)
    {
        return response()->json(
            $profileService->getProfile($request->user())
        );
    }

    public function update(
        Request $request,
        ProfileService $profileService
    ): JsonResponse {
        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'phone' => 'nullable|string',
            'avatar_url' => 'nullable|url',
        ]);

        return response()->json(
            $profileService->updateProfile(
                $request->user(),
                $data
            )
        );
    }

    public function uploadAvatar(Request $request, ProfileService $profileService)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return response()->json([
            'avatar' => $profileService->uploadAvatar(
                $request->user(),
                $request->file('avatar')
            ),
        ]);
    }
}
