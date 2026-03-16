<?php

namespace Tests\Feature\JobBoard;

use App\Models\User;
use App\Modules\JobBoard\Models\JobApplication;
use App\Modules\JobBoard\Models\JobListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected User $employer;

    protected User $applicant;

    protected JobListing $job;

    protected function setUp(): void
    {
        parent::setUp();

        Client::forceCreate([
            'name' => 'Test Personal Access Client',
            'secret' => Str::random(40),
            'redirect_uris' => [],
            'grant_types' => ['personal_access', 'refresh_token'],
            'revoked' => false,
            'provider' => 'users',
        ]);

        $this->employer = User::factory()->create();
        $this->applicant = User::factory()->create();

        $this->job = JobListing::create([
            'posted_by' => $this->employer->id,
            'title' => 'Software Engineer',
            'description' => 'Build things.',
            'type' => 'full_time',
            'status' => 'published',
        ]);
    }

    // ------ Apply ------

    public function test_applicant_can_apply_for_a_published_job(): void
    {
        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$this->job->id}/apply", [
                'cover_letter' => 'I am a great fit.',
            ])
            ->assertCreated()
            ->assertJsonPath('status', 'pending')
            ->assertJsonPath('job_id', $this->job->id);
    }

    public function test_applicant_cannot_apply_twice(): void
    {
        JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$this->job->id}/apply")
            ->assertUnprocessable()
            ->assertJsonPath('errors.job_id.0', 'You have already applied for this job.');
    }

    public function test_applicant_cannot_apply_to_draft_job(): void
    {
        $draft = JobListing::create([
            'posted_by' => $this->employer->id,
            'title' => 'Draft Job',
            'description' => 'Not yet open.',
            'type' => 'full_time',
            'status' => 'draft',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$draft->id}/apply")
            ->assertUnprocessable()
            ->assertJsonPath('errors.job_id.0', 'This job is no longer accepting applications.');
    }

    public function test_applicant_cannot_apply_to_closed_job(): void
    {
        $closed = JobListing::create([
            'posted_by' => $this->employer->id,
            'title' => 'Closed Job',
            'description' => 'Applications closed.',
            'type' => 'full_time',
            'status' => 'closed',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$closed->id}/apply")
            ->assertUnprocessable();
    }

    public function test_unauthenticated_cannot_apply(): void
    {
        $this->postJson("/api/job-board/jobs/{$this->job->id}/apply")
            ->assertUnauthorized();
    }

    public function test_apply_to_nonexistent_job_returns_404(): void
    {
        $this->actingAs($this->applicant, 'api')
            ->postJson('/api/job-board/jobs/99999/apply')
            ->assertNotFound();
    }

    // ------ My Applications ------

    public function test_applicant_can_view_own_applications(): void
    {
        JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $other = User::factory()->create();
        JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $other->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->getJson('/api/job-board/my-applications')
            ->assertOk()
            ->assertJsonCount(1);
    }

    // ------ Withdraw ------

    public function test_applicant_can_withdraw_own_application(): void
    {
        $application = JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->deleteJson("/api/job-board/applications/{$application->id}")
            ->assertOk();

        $this->assertDatabaseMissing('job_applications', ['id' => $application->id]);
    }

    public function test_applicant_cannot_withdraw_another_users_application(): void
    {
        $other = User::factory()->create();
        $application = JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $other->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->applicant, 'api')
            ->deleteJson("/api/job-board/applications/{$application->id}")
            ->assertNotFound();
    }

    // ------ Employer views ------

    public function test_employer_can_view_applications_for_own_job(): void
    {
        JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->employer, 'api')
            ->getJson("/api/job-board/jobs/{$this->job->id}/applications")
            ->assertOk()
            ->assertJsonCount(1);
    }

    public function test_employer_cannot_view_applications_of_another_employers_job(): void
    {
        $other = User::factory()->create();
        $otherJob = JobListing::create([
            'posted_by' => $other->id,
            'title' => 'Other Job',
            'description' => 'Desc',
            'type' => 'full_time',
            'status' => 'published',
        ]);

        JobApplication::create([
            'job_id' => $otherJob->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->employer, 'api')
            ->getJson("/api/job-board/jobs/{$otherJob->id}/applications")
            ->assertNotFound();
    }

    // ------ Update Status ------

    public function test_employer_can_update_application_status(): void
    {
        $application = JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", [
                'status' => 'shortlisted',
            ])
            ->assertOk()
            ->assertJsonPath('status', 'shortlisted');
    }

    public function test_employer_cannot_update_status_for_other_employers_application(): void
    {
        $other = User::factory()->create();
        $otherJob = JobListing::create([
            'posted_by' => $other->id,
            'title' => 'Other Job',
            'description' => 'Desc',
            'type' => 'full_time',
            'status' => 'published',
        ]);

        $application = JobApplication::create([
            'job_id' => $otherJob->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", [
                'status' => 'hired',
            ])
            ->assertNotFound();
    }

    public function test_update_status_validates_allowed_values(): void
    {
        $application = JobApplication::create([
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
            'status' => 'pending',
        ]);

        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", [
                'status' => 'invalid_status',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['status']);
    }

    public function test_full_hiring_workflow(): void
    {
        // Apply
        $this->actingAs($this->applicant, 'api')
            ->postJson("/api/job-board/jobs/{$this->job->id}/apply", [
                'cover_letter' => 'Hire me!',
            ])
            ->assertCreated();

        $application = JobApplication::first();

        // Employer: reviewing
        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", ['status' => 'reviewing'])
            ->assertJsonPath('status', 'reviewing');

        // Employer: shortlist
        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", ['status' => 'shortlisted'])
            ->assertJsonPath('status', 'shortlisted');

        // Employer: hire
        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/applications/{$application->id}/status", ['status' => 'hired'])
            ->assertJsonPath('status', 'hired');

        // Close the job
        $this->actingAs($this->employer, 'api')
            ->patchJson("/api/job-board/jobs/{$this->job->id}/close")
            ->assertJsonPath('status', 'closed');
    }
}
