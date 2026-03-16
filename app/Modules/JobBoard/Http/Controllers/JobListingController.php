<?php

namespace App\Modules\JobBoard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\JobBoard\Http\Requests\CreateJobListingRequest;
use App\Modules\JobBoard\Http\Requests\UpdateJobListingRequest;
use App\Modules\JobBoard\Models\JobListing;
use App\Modules\JobBoard\Services\JobListingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobListingController extends Controller
{
    public function __construct(protected JobListingService $service) {}

    /**
     * Browse published jobs (public).
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->browse($request->only(
            'search', 'category_id', 'type', 'is_remote'
        )));
    }

    /**
     * View a single published job.
     */
    public function show(int $jobId): JsonResponse
    {
        $job = JobListing::with('category', 'poster')->findOrFail($jobId);

        return response()->json($job);
    }

    /**
     * Employer: list their own job postings.
     */
    public function myListings(Request $request): JsonResponse
    {
        return response()->json($this->service->myListings($request->user()));
    }

    /**
     * Employer: create a job listing.
     */
    public function store(CreateJobListingRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->create($request->user(), $request->validated()),
            201
        );
    }

    /**
     * Employer: update a job listing they own.
     */
    public function update(UpdateJobListingRequest $request, int $jobId): JsonResponse
    {
        $job = JobListing::where('posted_by', $request->user()->id)->findOrFail($jobId);

        return response()->json($this->service->update($job, $request->validated()));
    }

    /**
     * Employer: publish a draft listing.
     */
    public function publish(Request $request, int $jobId): JsonResponse
    {
        $job = JobListing::where('posted_by', $request->user()->id)->findOrFail($jobId);

        return response()->json($this->service->publish($job));
    }

    /**
     * Employer: close a listing.
     */
    public function close(Request $request, int $jobId): JsonResponse
    {
        $job = JobListing::where('posted_by', $request->user()->id)->findOrFail($jobId);

        return response()->json($this->service->close($job));
    }

    /**
     * Employer: delete a listing.
     */
    public function destroy(Request $request, int $jobId): JsonResponse
    {
        $job = JobListing::where('posted_by', $request->user()->id)->findOrFail($jobId);

        $this->service->delete($job);

        return response()->json(['message' => 'Job listing deleted.']);
    }

    /**
     * Applicant: save or unsave a job.
     */
    public function toggleSave(Request $request, int $jobId): JsonResponse
    {
        $job   = JobListing::findOrFail($jobId);
        $saved = $this->service->toggleSave($request->user(), $job);

        return response()->json([
            'saved'   => $saved,
            'message' => $saved ? 'Job saved.' : 'Job removed from saved.',
        ]);
    }

    /**
     * Applicant: list their saved jobs.
     */
    public function savedJobs(Request $request): JsonResponse
    {
        return response()->json($this->service->savedJobs($request->user()));
    }
}
