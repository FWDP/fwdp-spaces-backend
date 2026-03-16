<?php

namespace Tests\Feature\Auth;

use App\Listeners\CreateTrialSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Prevent subscription listener from running — no plan seed in tests
        Event::fake([\App\Events\UserRegistered::class]);
    }

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Peter Brion',
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email']]);

        $this->assertDatabaseHas('users', ['email' => 'peter@example.com']);
    }

    public function test_register_fails_with_missing_name(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_register_fails_with_invalid_email(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Peter Brion',
            'email'                 => 'not-an-email',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'peter@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Another Peter',
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_register_fails_when_passwords_do_not_match(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Peter Brion',
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'WrongPassword!',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_register_fails_with_missing_password(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'  => 'Peter Brion',
            'email' => 'peter@example.com',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    public function test_password_is_hashed_on_registration(): void
    {
        $this->postJson('/api/auth/register', [
            'name'                  => 'Peter Brion',
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $user = User::where('email', 'peter@example.com')->first();
        $this->assertNotEquals('Password123!', $user->password);
        $this->assertTrue(\Hash::check('Password123!', $user->password));
    }

    public function test_user_registered_event_is_fired(): void
    {
        \Event::fake();

        $this->postJson('/api/auth/register', [
            'name'                  => 'Peter Brion',
            'email'                 => 'peter@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        \Event::assertDispatched(\App\Events\UserRegistered::class);
    }
}
