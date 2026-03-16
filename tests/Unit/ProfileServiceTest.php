<?php

namespace Tests\Unit;

use App\Core\Profile\Models\UserProfile;
use App\Core\Profile\Services\ProfileService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProfileService();
    }

    public function test_get_profile_returns_null_when_no_profile_exists(): void
    {
        $user = User::factory()->create();

        $result = $this->service->getProfile($user);

        $this->assertNull($result);
    }

    public function test_get_profile_returns_profile_when_exists(): void
    {
        $user = User::factory()->create();
        UserProfile::create(['user_id' => $user->id, 'first_name' => 'Peter']);

        $result = $this->service->getProfile($user);

        $this->assertInstanceOf(UserProfile::class, $result);
        $this->assertEquals('Peter', $result->first_name);
    }

    public function test_update_profile_creates_profile_when_none_exists(): void
    {
        $user = User::factory()->create();

        $profile = $this->service->updateProfile($user, ['first_name' => 'Peter']);

        $this->assertInstanceOf(UserProfile::class, $profile);
        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id, 'first_name' => 'Peter']);
    }

    public function test_update_profile_updates_existing_profile(): void
    {
        $user = User::factory()->create();
        UserProfile::create(['user_id' => $user->id, 'first_name' => 'Old Name']);

        $profile = $this->service->updateProfile($user, ['first_name' => 'Peter']);

        $this->assertEquals('Peter', $profile->first_name);
    }

    public function test_update_profile_preserves_existing_fields_when_not_provided(): void
    {
        $user = User::factory()->create();
        UserProfile::create([
            'user_id'    => $user->id,
            'first_name' => 'Peter',
            'last_name'  => 'Brion',
        ]);

        $profile = $this->service->updateProfile($user, ['first_name' => 'PJ']);

        $this->assertEquals('PJ', $profile->first_name);
        $this->assertEquals('Brion', $profile->last_name);
    }

    public function test_upload_avatar_stores_file_and_updates_profile(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $profile = $this->service->uploadAvatar($user, $file);

        $this->assertNotNull($profile->avatar_path ?? $profile->avatar_url);
    }

    public function test_upload_avatar_deletes_old_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $oldPath = "avatars/{$user->id}/old.jpg";
        Storage::disk('public')->put($oldPath, 'old content');

        UserProfile::create(['user_id' => $user->id, 'avatar_path' => $oldPath]);

        $file = UploadedFile::fake()->image('new.jpg');
        $this->service->uploadAvatar($user, $file);

        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_upload_avatar_creates_profile_when_none_exists(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->assertNull($this->service->getProfile($user));

        $file = UploadedFile::fake()->image('avatar.jpg');
        $profile = $this->service->uploadAvatar($user, $file);

        $this->assertDatabaseHas('user_profiles', ['user_id' => $user->id]);
    }
}
