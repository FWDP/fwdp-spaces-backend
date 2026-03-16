<?php

namespace Tests\Unit;

use App\Core\Auth\Services\AuthService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    public function test_register_creates_and_returns_user(): void
    {
        \Event::fake();

        $user = $this->service->register([
            'name'     => 'Peter Brion',
            'email'    => 'peter@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('peter@example.com', $user->email);
        $this->assertDatabaseHas('users', ['email' => 'peter@example.com']);
    }

    public function test_attempt_login_returns_user_with_correct_credentials(): void
    {
        User::factory()->create([
            'email'    => 'peter@example.com',
            'password' => \Hash::make('Password123!'),
        ]);

        $user = $this->service->attemptLogin('peter@example.com', 'Password123!');

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_attempt_login_returns_null_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'peter@example.com',
            'password' => \Hash::make('Password123!'),
        ]);

        $result = $this->service->attemptLogin('peter@example.com', 'WrongPassword');

        $this->assertNull($result);
    }

    public function test_attempt_login_returns_null_for_nonexistent_user(): void
    {
        $result = $this->service->attemptLogin('nobody@example.com', 'Password123!');

        $this->assertNull($result);
    }
}
