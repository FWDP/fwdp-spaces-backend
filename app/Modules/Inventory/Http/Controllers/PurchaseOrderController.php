<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\CreatePurchaseOrderRequest;
use App\Modules\Inventory\Models\PurchaseOrder;
use App\Modules\Inventory\Services\PurchaseOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(protected PurchaseOrderService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listOrders());
    }

    public function show(int $purchaseOrderId): JsonResponse
    {
        return response()->json($this->service->getOrder(PurchaseOrder::findOrFail($purchaseOrderId)));
    }

    public function store(CreatePurchaseOrderRequest $request): JsonResponse
    {
        return response()->json($this->service->createOrder($request->validated(), $request->user()->id), 201);
    }

    public function receive(Request $request, int $purchaseOrderId): JsonResponse
    {
        return response()->json($this->service->receiveOrder(PurchaseOrder::findOrFail($purchaseOrderId), $request->user()->id));
    }

    public function updateStatus(Request $request, int $purchaseOrderId): JsonResponse
    {
        $request->validate(['status' => 'required|in:draft,ordered,cancelled']);
        return response()->json($this->service->updateStatus(PurchaseOrder::findOrFail($purchaseOrderId), $request->input('status')));
    }
}
