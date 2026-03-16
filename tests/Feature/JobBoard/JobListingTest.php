<?php

namespace Tests\Feature\JobBoard;

use App\Models\User;
use App\Modules\JobBoard\Models\JobCategory;
use App\Modules\JobBoard\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class JobListingTest extends TestCase
{
    use RefreshDatabase;

    protected User $employer;
    protected User $applicant;

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

        $this->employer  = User::factory()->create();
        $this->applicant = User::factory()->create();
    }

    private function makeJob(array $overrides = []): JobListing
    {
        return JobListing::create(array_merge([
            'posted_by'   => $this->employer->id,
            'title'       => 'Software Engineer',
            'description' => 'Build great things.',
            'type'        => 'full_time',
            'status'      => 'published',
        ], $overrides));
    }

    // ------ Browse ------

    public function test_anyone_can_browse_published_jobs(): void
    {
        $this->makeJob(['status' => 'published']);
        $this->makeJob(['status' => 'draft']);

        $this->getJson('/api/job-board/jobs')
            ->assertOk()
            ->assertJsonPath('total', 1);
    }

    public function test_draft_jobs_are_not_visible_in_browse(): void
    {
        $this->makeJob(['status' => 'draft']);
        $this->makeJob(['status' => 'closed']);

        $this->getJson('/api/job-board/jobs')
            ->assertOk()
            ->assertJsonPath('total', 0);
    }

    public function test_browse_filters_by_search_term(): void
    {
        $this->makeJob(['title' => 'Laravel Developer', 'status' => 'published']);
        $this->makeJob(['title' => 'React Designer',    'status' => 'published']);

        $this->getJson('/api/job-board/jobs?search=Laravel')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('data.0.title', 'Laravel Developer');
    }

    public function test_browse_filters_by_category(): void
    {
        $cat = JobCategory::create(['name' => 'Tech', 'slug' => 'tech']);
        $this->makeJob(['category_id' => $cat->id, 'status' => 'published']);
        $this->makeJob(['status' => 'published']);

        $this->getJson("/api/job-board/jobs?category_id={$cat->id}")
            ->assertOk()
            ->assertJsonPath('total', 1);
    }

    public function test_browse_filters_by_type(): void
    {
        $this->makeJob(['type' => 'full_time',  'status' => 'published']);
        $this->makeJob(['type' => 'freelance',  'status' => 'published']);

        $this->getJson('/api/job-board/jobs?type=freelance')
            ->assertOk()
            ->assertJsonPath('total', 1);
    }

    public function test_browse_filters_remote_jobs(): void
    {
        $this->makeJob(['is_remote' => true,  'status' => 'published']);
        $this->makeJob(['is_remote' => false, 'status' => 'published']);

        $this->getJson('/api/job-board/jobs?is_remote=1')
            ->assertOk()
            ->assertJsonPath('total', 1);
    }

    public function test_single_job_can_be_viewed(): void
    {
        $job = $this->makeJob();

        $this->getJson("/api/job-board/jobs/{$job->id}")
            ->assertOk()
            ->assertJsonPath('title', 'Software Engineer');
    }

    public function test_viewing_nonexistent_job_returns_404(): void
    {
        $this->getJson('/api/job-board/jobs/99999')
            ->assertNotFound();
    }

    // ------ Employer CRUD ------

    public function test_employer_can_create_job_listing(): void
    {
        $this->actingAs($this->employer, 'api')
            ->postJson('/api/job-board/jobs', [
                'title'       => 'Backend Dev',
                'description' => 'Write APIs.',
                'type'        => 'full_time',
            ])
            ->assertCreated()
            ->assertJsonPath('title', 'Backend Dev')
            ->assertJsonPath('status', 'draft');
    }

    public function test_create_job_requires_title_description_type(): void
    {
        $this->actingAs($this->employer, 'api')
            ->postJson('/api/job-board/jobs', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'description', 'type']);
    }

    public function test_create_job_validates_salary_max_gte_min(): void
    {
        $this->actingAs($this->employer, 'api')
            ->postJson('/api/job-board/jobs', [
                'title'       => 'Job',
                'description' => 'Desc',
                'type'        => 'full_time',
                'salary_min'  => 50000,
                'salary_max'  => 10000,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['salary_max']);
    }

    public function test_create_job_validates_deadline_in_future(): void
    {
        $this->actingAs($this->employer, 'api')
            ->postJson('/api/job-board/jobs', [
                'title'       => 'Job',
                'description' => 'Desc',
                'type'        => 'full_time',
                'deadline'    => now()->subDay()->toDateString(),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['deadline']);
    }

    public function test_unauthenticated_cannot_create_job(): void
    {
        $this->postJson('/api/job-board/jobs', [
            'title'       => 'Backend Dev',
            'description' => 'Write APIs.',
            'type'        => 'full_time',
        ])->assertUnauthorized();
    }

    public function test_employer_can_update_own_job(): void
    {
        $job = $this->makeJob(['status' => 'draft']);

        $this->actingAs($this->employer, 'api')
            ->putJson("/api/job-board/jobs/{$job->id}", ['title' => 'Senior Engineer'])
            ->assertOk()
            ->assertJsonPath('title', 'Senior Engineer');
    }

    public function test_employer_cannot_update_another_employers_job(): void
    {
        $other = User::factory()->create();
        $job   = $this->makeJob(['posted_by' => $other->id]);

        $this->actingAs($this->employer, 'api')
            ->putJson("/api/job-board/jobs/{$job->id}", ['title' => 'Hacked'])
            ->assertNotFound();
    }

    public function test_employer_can_publish_draft_job(): void
    {
        $job = $this->makeJob(['status' => 'draft']);

        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/jobs/{$job->id}/publish")
            ->assertOk()
            ->assertJsonPath('status', 'published');
    }

    public function test_employer_can_close_job(): void
    {
        $job = $this->makeJob(['status' => 'published']);

        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/jobs/{$job->id}/close")
            ->assertOk()
            ->assertJsonPath('status', 'closed');
    }

    public function test_employer_can_delete_job(): void
    {
        $job = $this->makeJob();

        $this->actingAs($this->employer, 'api')
            ->deleteJson("/api/job-board/jobs/{$job->id}")
            ->assertOk();

        $this->assertDatabaseMissing('job_listings', ['id' => $job->id]);
    }

    public function test_employer_can_list_own_jobs(): void
    {
        $this->makeJob();
        $this->makeJob(['title' => 'PM Role']);
        $other = User::factory()->create();
        $this->makeJob(['posted_by' => $other->id]);

        $this->actingAs($this->employer, 'api')
            ->getJson('/api/job-board/my-jobs')
            ->assertOk()
            ->assertJsonCount(2);
    }

    // ------ Save/Unsave ------

    public function test_user_can_save_a_job(): void
    {
        $job = $this->makeJob();

        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$job->id}/save")
            ->assertOk()
            ->assertJsonPath('saved', true);

        $this->assertDatabaseHas('saved_jobs', [
            'user_id' => $this->applicant->id,
            'job_id'  => $job->id,
        ]);
    }

    public function test_user_can_unsave_a_saved_job(): void
    {
        $job = $this->makeJob();
        \App\Modules\JobBoard\Models\SavedJob::create([
            'user_id' => $this->applicant->id,
            'job_id'  => $job->id,
            'saved_at' => now(),
        ]);

        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$job->id}/save")
            ->assertOk()
            ->assertJsonPath('saved', false);

        $this->assertDatabaseMissing('saved_jobs', [
            'user_id' => $this->applicant->id,
            'job_id'  => $job->id,
        ]);
    }

    public function test_user_can_list_saved_jobs(): void
    {
        $job1 = $this->makeJob(['title' => 'Job A']);
        $job2 = $this->makeJob(['title' => 'Job B']);

        \App\Modules\JobBoard\Models\SavedJob::create(['user_id' => $this->applicant->id, 'job_id' => $job1->id, 'saved_at' => now()]);
        \App\Modules\JobBoard\Models\SavedJob::create(['user_id' => $this->applicant->id, 'job_id' => $job2->id, 'saved_at' => now()]);

        $this->actingAs($this->applicant, 'api')
            ->getJson('/api/job-board/saved-jobs')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_unauthenticated_cannot_save_job(): void
    {
        $job = $this->makeJob();

        $this->postJson("/api/job-board/jobs/{$job->id}/save")
            ->assertUnauthorized();
    }
}
