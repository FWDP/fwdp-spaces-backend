<?php

namespace App\Modules\JobBoard\Services;

use App\Models\User;
use App\Modules\JobBoard\Models\JobListing;
use App\Modules\JobBoard\Models\SavedJob;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class JobListingService
{
    /**
     * Browse published jobs with optional filters.
     */
    public function browse(array $filters = []): LengthAwarePaginator
    {
        $query = JobListing::published()
            ->with('category', 'poster')
            ->orderByDesc('created_at');

        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('location', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_remote'])) {
            $query->where('is_remote', (bool) $filters['is_remote']);
        }

        return $query->paginate(15);
    }

    /**
     * Get jobs posted by a specific user (employer view).
     */
    public function myListings(User $user): Collection
    {
        return JobListing::where('posted_by', $user->id)
            ->with('category')
            ->withCount('applications')
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(User $poster, array $data): JobListing
    {
        $job = JobListing::create(array_merge($data, ['posted_by' => $poster->id]));

        return $job->refresh();
    }

    public function update(JobListing $job, array $data): JobListing
    {
        $job->update($data);

        return $job->fresh(['category']);
    }

    public function publish(JobListing $job): JobListing
    {
        $job->update(['status' => 'published']);

        return $job->fresh();
    }

    public function close(JobListing $job): JobListing
    {
        $job->update(['status' => 'closed']);

        return $job->fresh();
    }

    public function delete(JobListing $job): void
    {
        $job->delete();
    }

    public function toggleSave(User $user, JobListing $job): bool
    {
        $existing = SavedJob::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return false; // unsaved
        }

        SavedJob::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'saved_at' => now(),
        ]);

        return true; // saved
    }

    public function savedJobs(User $user): Collection
    {
        return SavedJob::where('user_id', $user->id)
            ->with('job.category')
            ->orderByDesc('saved_at')
            ->get();
    }
}
