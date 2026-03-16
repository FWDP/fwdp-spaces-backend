<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\CreateProductRequest;
use App\Modules\Inventory\Http\Requests\UpdateProductRequest;
use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listProducts());
    }

    public function show(int $productId): JsonResponse
    {
        return response()->json($this->service->getProduct(Product::findOrFail($productId)));
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        return response()->json($this->service->createProduct($request->validated()), 201);
    }

    public function update(UpdateProductRequest $request, int $productId): JsonResponse
    {
        return response()->json($this->service->updateProduct(Product::findOrFail($productId), $request->validated()));
    }

    public function destroy(int $productId): JsonResponse
    {
        $this->service->deleteProduct(Product::findOrFail($productId));
        return response()->json(['message' => 'Product deleted.']);
    }
}
