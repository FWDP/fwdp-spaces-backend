<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Laravel\Passport\Client::forceCreate([
            'name'          => 'Test Personal Access Client',
            'secret'        => \Illuminate\Support\Str::random(40),
            'redirect_uris' => [],
            'grant_types'   => ['personal_access', 'refresh_token'],
            'revoked'       => false,
            'provider'      => 'users',
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email'    => 'peter@example.com',
            'password' => \Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'peter@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user', 'token']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'peter@example.com',
            'password' => \Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'peter@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Unauthorized. Invalid credentials.']);
    }

    public function test_login_fails_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'nobody@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_fails_with_missing_email(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'password' => 'Password123!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_login_fails_with_missing_password(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'peter@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_login_fails_with_invalid_email_format(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email'    => 'not-valid',
            'password' => 'Password123!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_login_returns_token(): void
    {
        User::factory()->create([
            'email'    => 'peter@example.com',
            'password' => \Hash::make('Password123!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email'    => 'peter@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertNotEmpty($response->json('token'));
    }
}
