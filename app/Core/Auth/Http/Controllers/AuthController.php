<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Services\PassportService;
use App\Core\Membership\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected PassportService $passportService;

    public function __construct(PassportService $passportService)
    {
        $this->passportService = $passportService;
    }

    public function register(
        Request $request,
        User $user,
    )
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::MSME_USER->value,
        ]);

        return response()->json([
            'user' => $user,
            'token' => $this->passportService->createPersonalAccessToken($user),
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function login(
        Request $request,
        User $user,
    )
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);

        if (! $user->where('email', $credentials["email"])->first() ||
        ! Hash::check($credentials['password'],
            $user->where('email', $credentials['email'])->first()->password)) {
            return response()->json([
                'message' => 'Unauthorized. Invalid credentials.',
            ], 401);
        }

        return response()->json([
            'user' => $user->where('email', $credentials['email'])->first(),
            'token' => $this->passportService->createPersonalAccessToken(
                $user->where('email', $credentials['email'])->first(), 'login_token'),
        ]);
    }

    public function logout(Request $request)
    {
        $this->passportService->revokeCurrentToken($request->user());

        return response()->json([
            'message' => 'You have been successfully logged out!'
        ], 204);
    }

}
