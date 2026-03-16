<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\AdjustStockRequest;
use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Models\Warehouse;
use App\Modules\Inventory\Services\StockService;
use Illuminate\Http\JsonResponse;

class StockController extends Controller
{
    public function __construct(protected StockService $service) {}

    public function level(int $productId, int $warehouseId): JsonResponse
    {
        $product   = Product::findOrFail($productId);
        $warehouse = Warehouse::findOrFail($warehouseId);
        return response()->json($this->service->getStockLevel($product, $warehouse));
    }

    public function adjust(AdjustStockRequest $request, int $productId, int $warehouseId): JsonResponse
    {
        $product   = Product::findOrFail($productId);
        $warehouse = Warehouse::findOrFail($warehouseId);

        $movement = $this->service->adjust(
            $product,
            $warehouse,
            $request->input('type'),
            $request->input('quantity'),
            $request->user()->id,
            $request->input('note')
        );

        return response()->json($movement, 201);
    }

    public function movements(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);
        return response()->json($this->service->getMovements($product));
    }
}
