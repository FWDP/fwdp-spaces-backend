<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\CreateCategoryRequest;
use App\Modules\Inventory\Services\ProductService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listCategories());
    }

    public function store(CreateCategoryRequest $request): JsonResponse
    {
        return response()->json($this->service->createCategory($request->validated()), 201);
    }
}
