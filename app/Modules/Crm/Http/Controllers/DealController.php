<?php

namespace App\Modules\Crm\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Crm\Http\Requests\CreateDealRequest;
use App\Modules\Crm\Http\Requests\UpdateDealRequest;
use App\Modules\Crm\Models\Deal;
use App\Modules\Crm\Services\DealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(protected DealService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listDeals());
    }

    public function show(int $dealId): JsonResponse
    {
        return response()->json($this->service->getDeal(Deal::findOrFail($dealId)));
    }

    public function store(CreateDealRequest $request): JsonResponse
    {
        return response()->json($this->service->createDeal($request->validated()), 201);
    }

    public function update(UpdateDealRequest $request, int $dealId): JsonResponse
    {
        return response()->json($this->service->updateDeal(Deal::findOrFail($dealId), $request->validated()));
    }

    public function updateStage(Request $request, int $dealId): JsonResponse
    {
        $request->validate(['stage' => 'required|in:new,qualified,proposal,negotiation,won,lost']);

        return response()->json($this->service->updateStage(Deal::findOrFail($dealId), $request->input('stage')));
    }

    public function destroy(int $dealId): JsonResponse
    {
        $this->service->deleteDeal(Deal::findOrFail($dealId));

        return response()->json(['message' => 'Deal deleted.']);
    }
}
