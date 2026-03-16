<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Http\Requests\CreateSupplierRequest;
use App\Modules\Inventory\Http\Requests\UpdateSupplierRequest;
use App\Modules\Inventory\Models\Supplier;
use App\Modules\Inventory\Services\SupplierService;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listSuppliers());
    }

    public function store(CreateSupplierRequest $request): JsonResponse
    {
        return response()->json($this->service->createSupplier($request->validated()), 201);
    }

    public function update(UpdateSupplierRequest $request, int $supplierId): JsonResponse
    {
        return response()->json($this->service->updateSupplier(Supplier::findOrFail($supplierId), $request->validated()));
    }

    public function destroy(int $supplierId): JsonResponse
    {
        $this->service->deleteSupplier(Supplier::findOrFail($supplierId));

        return response()->json(['message' => 'Supplier deactivated.']);
    }
}
