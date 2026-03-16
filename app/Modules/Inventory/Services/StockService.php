<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Models\StockLevel;
use App\Modules\Inventory\Models\StockMovement;
use App\Modules\Inventory\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function getStockLevel(Product $product, Warehouse $warehouse): StockLevel
    {
        $stock = StockLevel::query()
            ->where('product_id', $product->id)
            ->where('warehouse_id', $warehouse->id)
            ->first();

        if (!$stock) {
            $stock = new StockLevel();
            $stock->product_id  = $product->id;
            $stock->warehouse_id = $warehouse->id;
            $stock->quantity    = 0;
            $stock->reserved    = 0;
            $stock->save();
        }

        return $stock;
    }

    public function adjust(Product $product, Warehouse $warehouse, string $type, float $quantity, int $userId, ?string $note = null): StockMovement
    {
        return DB::transaction(function () use ($product, $warehouse, $type, $quantity, $userId, $note) {
            $stock = $this->getStockLevel($product, $warehouse);

            match ($type) {
                'in'         => $stock->increment('quantity', $quantity),
                'out'        => $stock->decrement('quantity', $quantity),
                'adjustment' => $stock->update(['quantity' => $quantity]),
                default      => null,
            };

            return StockMovement::query()->create([
                'product_id'   => $product->id,
                'warehouse_id' => $warehouse->id,
                'user_id'      => $userId,
                'type'         => $type,
                'quantity'     => $quantity,
                'note'         => $note,
            ]);
        });
    }

    public function getMovements(Product $product): Collection
    {
        return StockMovement::query()
            ->with(['warehouse', 'user'])
            ->where('product_id', $product->id)
            ->latest()
            ->get();
    }

    public function listWarehouses(): Collection
    {
        return Warehouse::query()->where('is_active', true)->get();
    }

    public function createWarehouse(array $data): Warehouse
    {
        return Warehouse::query()->create($data);
    }
}
