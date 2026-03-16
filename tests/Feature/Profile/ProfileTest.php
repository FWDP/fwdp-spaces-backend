<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use App\Core\Profile\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_their_profile(): void
    {
        $user = User::factory()->create();
        UserProfile::create([
            'user_id'    => $user->id,
            'first_name' => 'Peter',
            'last_name'  => 'Brion',
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'Peter']);
    }

    public function test_unauthenticated_user_cannot_view_profile(): void
    {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
    }

    public function test_profile_returns_null_when_not_yet_created(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/profile');

        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_user_can_update_their_profile(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/profile', [
            'first_name' => 'Peter',
            'last_name'  => 'Brion',
            'phone'      => '09123456789',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'Peter', 'last_name' => 'Brion']);

        $this->assertDatabaseHas('user_profiles', [
            'user_id'    => $user->id,
            'first_name' => 'Peter',
        ]);
    }

    public function test_profile_update_creates_profile_if_not_exists(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $this->assertNull($user->profile);

        $this->postJson('/api/profile', ['first_name' => 'Peter']);

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);
    }

    public function test_profile_update_rejects_invalid_avatar_url(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/profile', [
            'avatar_url' => 'not-a-url',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['avatar_url']);
    }

    public function test_profile_update_accepts_valid_avatar_url(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->postJson('/api/profile', [
            'avatar_url' => 'https://example.com/avatar.jpg',
        ]);

        $response->assertStatus(200);
    }

    public function test_user_can_upload_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Passport::actingAs($user);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson('/api/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertStatus(200);
        Storage::disk('public')->assertExists("avatars/{$user->id}/{$file->hashName()}");
    }

    public function test_avatar_upload_rejects_non_image(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->postJson('/api/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['avatar']);
    }

    public function test_avatar_upload_rejects_files_over_2mb(): void
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $file = UploadedFile::fake()->image('big.jpg')->size(3000);

        $response = $this->postJson('/api/profile/avatar', [
            'avatar' => $file,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['avatar']);
    }

    public function test_old_avatar_is_deleted_when_uploading_new_one(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $oldPath = "avatars/{$user->id}/old_avatar.jpg";
        Storage::disk('public')->put($oldPath, 'fake content');

        UserProfile::create([
            'user_id'     => $user->id,
            'avatar_path' => $oldPath,
        ]);

        Passport::actingAs($user);

        $file = UploadedFile::fake()->image('new_avatar.jpg');
        $this->postJson('/api/profile/avatar', ['avatar' => $file]);

        Storage::disk('public')->assertMissing($oldPath);
    }
}
