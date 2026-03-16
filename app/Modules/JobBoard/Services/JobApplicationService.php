<?php

namespace App\Modules\JobBoard\Services;

use App\Models\User;
use App\Modules\JobBoard\Models\JobApplication;
use App\Modules\JobBoard\Models\JobListing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class JobApplicationService
{
    /**
     * Submit a new application. Prevents duplicate applications.
     */
    public function apply(User $applicant, JobListing $job, array $data): JobApplication
    {
        if ($job->status !== 'published') {
            throw ValidationException::withMessages([
                'job_id' => ['This job is no longer accepting applications.'],
            ]);
        }

        $exists = JobApplication::where('job_id', $job->id)
            ->where('applicant_id', $applicant->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'job_id' => ['You have already applied for this job.'],
            ]);
        }

        return JobApplication::create(array_merge($data, [
            'job_id'       => $job->id,
            'applicant_id' => $applicant->id,
            'status'       => 'pending',
        ]));
    }

    /**
     * Applicant's own applications.
     */
    public function myApplications(User $applicant): Collection
    {
        return JobApplication::where('applicant_id', $applicant->id)
            ->with('job.category')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Applications received for a specific job (employer view).
     */
    public function forJob(JobListing $job): Collection
    {
        return JobApplication::where('job_id', $job->id)
            ->with('applicant')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Update application status (employer action).
     */
    public function updateStatus(JobApplication $application, string $status): JobApplication
    {
        $application->update(['status' => $status]);
        return $application->fresh();
    }

    /**
     * Withdraw an application (applicant action).
     */
    public function withdraw(JobApplication $application): void
    {
        $application->delete();
    }
}
