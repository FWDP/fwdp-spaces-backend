<?php

namespace App\Modules\JobBoard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\JobBoard\Http\Requests\CreateJobApplicationRequest;
use App\Modules\JobBoard\Models\JobApplication;
use App\Modules\JobBoard\Models\JobListing;
use App\Modules\JobBoard\Services\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function __construct(protected JobApplicationService $service) {}

    /**
     * Applicant: submit an application.
     */
    public function apply(CreateJobApplicationRequest $request, int $jobId): JsonResponse
    {
        $job = JobListing::findOrFail($jobId);

        return response()->json(
            $this->service->apply($request->user(), $job, $request->validated()),
            201
        );
    }

    /**
     * Applicant: view own applications.
     */
    public function myApplications(Request $request): JsonResponse
    {
        return response()->json($this->service->myApplications($request->user()));
    }

    /**
     * Applicant: withdraw an application.
     */
    public function withdraw(Request $request, int $applicationId): JsonResponse
    {
        $application = JobApplication::where('applicant_id', $request->user()->id)
            ->findOrFail($applicationId);

        $this->service->withdraw($application);

        return response()->json(['message' => 'Application withdrawn.']);
    }

    /**
     * Employer: view applications for their job.
     */
    public function forJob(Request $request, int $jobId): JsonResponse
    {
        $job = JobListing::where('posted_by', $request->user()->id)->findOrFail($jobId);

        return response()->json($this->service->forJob($job));
    }

    /**
     * Employer: update application status.
     */
    public function updateStatus(Request $request, int $applicationId): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,hired',
        ]);

        // Ensure the employer owns the job this application belongs to
        $application = JobApplication::whereHas('job', function ($q) use ($request) {
            $q->where('posted_by', $request->user()->id);
        })->findOrFail($applicationId);

        return response()->json($this->service->updateStatus($application, $data['status']));
    }
}
