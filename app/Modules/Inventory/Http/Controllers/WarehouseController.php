<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\CreateWarehouseRequest;
use App\Modules\Inventory\Services\StockService;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function __construct(protected StockService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listWarehouses());
    }

    public function store(CreateWarehouseRequest $request): JsonResponse
    {
        return response()->json($this->service->createWarehouse($request->validated()), 201);
    }
}
