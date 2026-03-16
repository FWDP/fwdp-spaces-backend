<?php

namespace App\Core\Auth\Http\Controllers;

use App\Core\Auth\Services\AuthService;
use App\Core\Auth\Services\PassportService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $authService;
    protected PassportService $passportService;

    public function __construct(
        AuthService $authService,
        PassportService $passportService,
    ) {
        $this->authService = $authService;
        $this->passportService = $passportService;
    }

    public function register(
        Request $request
    )
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = $this->authService->register($data);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * @throws ConnectionException
     */
    public function login(
        Request $request,
    )
    {
        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string'
        ]);

        $user = $this->authService->attemptLogin(
            $credentials['email'],
            $credentials['password']
        );

        if (!$user) return response()->json(['message' => 'Unauthorized. Invalid credentials.'], 401);

        return response()->json([
            'user' => $user,
            'token' => $this->passportService->createPersonalAccessToken($user, 'login_token'),
        ]);
    }

    public function logout(Request $request)
    {
        $this->passportService->revokeCurrentToken($request->user());

        return response()->json([
            'message' => 'You have been successfully logged out!'
        ]);
    }

}
