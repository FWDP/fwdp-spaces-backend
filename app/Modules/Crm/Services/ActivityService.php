<?php

namespace App\Modules\Crm\Services;

use App\Modules\Crm\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    public function listActivities(?int $contactId = null, ?int $dealId = null): Collection
    {
        $query = Activity::query()->with(['contact', 'deal'])->orderByDesc('id');

        if ($contactId) $query->where('contact_id', $contactId);
        if ($dealId)    $query->where('deal_id', $dealId);

        return $query->get();
    }

    public function createActivity(array $data): Activity
    {
        return Activity::query()->create($data);
    }

    public function complete(Activity $activity): Activity
    {
        $activity->update(['completed_at' => now()]);
        return $activity->fresh();
    }

    public function deleteActivity(Activity $activity): void
    {
        $activity->delete();
    }
}
