<?php

use App\Modules\Inventory\Http\Controllers\CategoryController;
use App\Modules\Inventory\Http\Controllers\ProductController;
use App\Modules\Inventory\Http\Controllers\PurchaseOrderController;
use App\Modules\Inventory\Http\Controllers\StockController;
use App\Modules\Inventory\Http\Controllers\SupplierController;
use App\Modules\Inventory\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/inventory')->middleware('auth:api')->group(function () {

    // Product Categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{productId}', [ProductController::class, 'show']);
    Route::put('products/{productId}', [ProductController::class, 'update']);
    Route::delete('products/{productId}', [ProductController::class, 'destroy']);

    // Warehouses
    Route::get('warehouses', [WarehouseController::class, 'index']);
    Route::post('warehouses', [WarehouseController::class, 'store']);

    // Stock
    Route::get('stock/{productId}/movements', [StockController::class, 'movements']);
    Route::get('stock/{productId}/{warehouseId}', [StockController::class, 'level']);
    Route::post('stock/{productId}/{warehouseId}/adjust', [StockController::class, 'adjust']);

    // Suppliers
    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::post('suppliers', [SupplierController::class, 'store']);
    Route::put('suppliers/{supplierId}', [SupplierController::class, 'update']);
    Route::delete('suppliers/{supplierId}', [SupplierController::class, 'destroy']);

    // Purchase Orders
    Route::get('purchase-orders', [PurchaseOrderController::class, 'index']);
    Route::post('purchase-orders', [PurchaseOrderController::class, 'store']);
    Route::get('purchase-orders/{purchaseOrderId}', [PurchaseOrderController::class, 'show']);
    Route::patch('purchase-orders/{purchaseOrderId}/status', [PurchaseOrderController::class, 'updateStatus']);
    Route::post('purchase-orders/{purchaseOrderId}/receive', [PurchaseOrderController::class, 'receive']);
});
