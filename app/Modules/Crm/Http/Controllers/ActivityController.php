<?php

namespace App\Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Crm\Http\Requests\CreateActivityRequest;
use App\Modules\Crm\Models\Activity;
use App\Modules\Crm\Services\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function __construct(protected ActivityService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json($this->service->listActivities(
            $request->integer('contact_id') ?: null,
            $request->integer('deal_id') ?: null,
        ));
    }

    public function store(CreateActivityRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        return response()->json($this->service->createActivity($data), 201);
    }

    public function complete(int $activityId): JsonResponse
    {
        return response()->json($this->service->complete(Activity::findOrFail($activityId)));
    }

    public function destroy(int $activityId): JsonResponse
    {
        $this->service->deleteActivity(Activity::findOrFail($activityId));

        return response()->json(['message' => 'Activity deleted.']);
    }
}
