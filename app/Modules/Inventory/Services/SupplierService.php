<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

class SupplierService
{
    public function listSuppliers(): Collection
    {
        return Supplier::query()->where('is_active', true)->get();
    }

    public function createSupplier(array $data): Supplier
    {
        return Supplier::query()->create($data);
    }

    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier->fresh();
    }

    public function deleteSupplier(Supplier $supplier): void
    {
        $supplier->update(['is_active' => false]);
    }
}
