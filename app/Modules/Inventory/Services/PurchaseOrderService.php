<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\PurchaseOrder;
use App\Modules\Inventory\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseOrderService
{
    public function __construct(protected StockService $stockService) {}

    public function listOrders(): Collection
    {
        return PurchaseOrder::query()->with(['supplier', 'warehouse', 'createdBy'])->latest()->get();
    }

    public function getOrder(PurchaseOrder $order): PurchaseOrder
    {
        return $order->load(['supplier', 'warehouse', 'createdBy', 'items.product']);
    }

    public function createOrder(array $data, int $userId): PurchaseOrder
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = $data['items'] ?? [];
            $total = collect($items)->sum(fn ($i) => $i['quantity'] * $i['unit_cost']);

            $order = PurchaseOrder::query()->create([
                'supplier_id'  => $data['supplier_id'],
                'warehouse_id' => $data['warehouse_id'],
                'created_by'   => $userId,
                'reference'    => 'PO-' . strtoupper(Str::random(8)),
                'status'       => 'draft',
                'total'        => $total,
                'expected_at'  => $data['expected_at'] ?? null,
            ]);

            foreach ($items as $item) {
                PurchaseOrderItem::query()->create([
                    'purchase_order_id' => $order->id,
                    'product_id'        => $item['product_id'],
                    'quantity'          => $item['quantity'],
                    'unit_cost'         => $item['unit_cost'],
                    'total'             => $item['quantity'] * $item['unit_cost'],
                ]);
            }

            return $order->load(['supplier', 'warehouse', 'items.product']);
        });
    }

    public function receiveOrder(PurchaseOrder $order, int $userId): PurchaseOrder
    {
        return DB::transaction(function () use ($order, $userId) {
            if ($order->status !== 'ordered') {
                abort(422, 'Only ordered purchase orders can be received.');
            }

            foreach ($order->items as $item) {
                $this->stockService->adjust(
                    $item->product,
                    $order->warehouse,
                    'in',
                    $item->quantity,
                    $userId,
                    "Received from PO {$order->reference}"
                );
            }

            $order->update(['status' => 'received']);
            return $order->fresh(['supplier', 'warehouse', 'items.product']);
        });
    }

    public function updateStatus(PurchaseOrder $order, string $status): PurchaseOrder
    {
        $order->update(['status' => $status]);
        return $order->fresh();
    }
}
