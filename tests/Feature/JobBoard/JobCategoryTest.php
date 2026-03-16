<?php

namespace Tests\Feature\JobBoard;

use App\Models\User;
use App\Modules\JobBoard\Models\JobCategory;
use App\Modules\JobBoard\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class JobCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        \Laravel\Passport\Client::forceCreate([
            'name'          => 'Test Personal Access Client',
            'secret'        => Str::random(40),
            'redirect_uris' => [],
            'grant_types'   => ['personal_access', 'refresh_token'],
            'revoked'       => false,
            'provider'      => 'users',
        ]);

        $this->admin = User::factory()->create(['role' => 'ADMIN']);
        $this->user  = User::factory()->create(['role' => 'USER']);
    }

    public function test_anyone_can_list_categories(): void
    {
        JobCategory::create(['name' => 'Tech', 'slug' => 'tech']);
        JobCategory::create(['name' => 'Finance', 'slug' => 'finance']);

        $this->getJson('/api/job-board/categories')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_admin_can_create_category(): void
    {
        $this->actingAs($this->admin, 'api')
            ->postJson('/api/job-board/categories', ['name' => 'Engineering'])
            ->assertCreated()
            ->assertJsonPath('name', 'Engineering')
            ->assertJsonPath('slug', 'engineering');
    }

    public function test_category_slug_is_auto_generated(): void
    {
        $this->actingAs($this->admin, 'api')
            ->postJson('/api/job-board/categories', ['name' => 'Software Development'])
            ->assertCreated()
            ->assertJsonPath('slug', 'software-development');
    }

    public function test_non_admin_cannot_create_category(): void
    {
        $this->actingAs($this->user, 'api')
            ->postJson('/api/job-board/categories', ['name' => 'HR'])
            ->assertForbidden();
    }

    public function test_unauthenticated_cannot_create_category(): void
    {
        $this->postJson('/api/job-board/categories', ['name' => 'HR'])
            ->assertUnauthorized();
    }

    public function test_create_category_requires_name(): void
    {
        $this->actingAs($this->admin, 'api')
            ->postJson('/api/job-board/categories', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_create_category_name_must_be_unique(): void
    {
        JobCategory::create(['name' => 'Tech', 'slug' => 'tech']);

        $this->actingAs($this->admin, 'api')
            ->postJson('/api/job-board/categories', ['name' => 'Tech'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function test_admin_can_update_category(): void
    {
        $cat = JobCategory::create(['name' => 'Tech', 'slug' => 'tech']);

        $this->actingAs($this->admin, 'api')
            ->putJson("/api/job-board/categories/{$cat->id}", ['name' => 'Technology'])
            ->assertOk()
            ->assertJsonPath('name', 'Technology')
            ->assertJsonPath('slug', 'technology');
    }

    public function test_admin_can_delete_category(): void
    {
        $cat = JobCategory::create(['name' => 'Temp', 'slug' => 'temp']);

        $this->actingAs($this->admin, 'api')
            ->deleteJson("/api/job-board/categories/{$cat->id}")
            ->assertOk();

        $this->assertDatabaseMissing('job_categories', ['id' => $cat->id]);
    }

    public function test_category_listing_includes_count(): void
    {
        $cat = JobCategory::create(['name' => 'Tech', 'slug' => 'tech']);
        $poster = User::factory()->create();

        JobListing::create([
            'category_id' => $cat->id,
            'posted_by'   => $poster->id,
            'title'       => 'Dev Job',
            'description' => 'A job',
            'type'        => 'full_time',
            'status'      => 'published',
        ]);

        $this->getJson('/api/job-board/categories')
            ->assertOk()
            ->assertJsonPath('0.listings_count', 1);
    }
}
