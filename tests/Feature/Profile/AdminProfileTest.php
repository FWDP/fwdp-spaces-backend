<?php

namespace Tests\Feature\Profile;

use App\Core\Profile\Models\UserProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'ADMIN']);
    }

    public function test_admin_can_list_all_users_with_profiles(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->count(5)->create();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/profiles');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'total', 'per_page']);
    }

    public function test_non_admin_cannot_access_admin_profiles(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        Passport::actingAs($user);

        $response = $this->getJson('/api/admin/profiles');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_admin_profiles(): void
    {
        $response = $this->getJson('/api/admin/profiles');

        $response->assertStatus(401);
    }

    public function test_admin_can_search_users_by_name(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['name' => 'Peter Brion']);
        User::factory()->create(['name' => 'John Doe']);
        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/profiles?q=Peter');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Peter Brion', $data[0]['name']);
    }

    public function test_admin_can_search_users_by_email(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['email' => 'peter@fwdp.org']);
        User::factory()->create(['email' => 'john@example.com']);
        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/profiles?q=fwdp');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
    }

    public function test_admin_can_view_single_user_profile(): void
    {
        $admin = $this->makeAdmin();
        $user = User::factory()->create();
        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Peter',
            'last_name' => 'Brion',
        ]);
        Passport::actingAs($admin);

        $response = $this->getJson("/api/admin/profiles/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['first_name' => 'Peter']);
    }

    public function test_admin_show_returns_404_for_nonexistent_user(): void
    {
        $admin = $this->makeAdmin();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/profiles/99999');

        $response->assertStatus(404);
    }

    public function test_admin_list_is_paginated(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->count(25)->create();
        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/profiles');

        $response->assertStatus(200);
        $this->assertCount(20, $response->json('data'));
        $this->assertEquals(26, $response->json('total')); // 25 + admin
    }
}
