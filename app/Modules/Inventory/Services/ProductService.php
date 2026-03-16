<?php

namespace App\Modules\Inventory\Services;

use App\Modules\Inventory\Models\Product;
use App\Modules\Inventory\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function listProducts(): Collection
    {
        return Product::query()->with(['category', 'supplier', 'unit'])->where('is_active', true)->get();
    }

    public function getProduct(Product $product): Product
    {
        return $product->load(['category', 'supplier', 'unit', 'stockLevels.warehouse']);
    }

    public function createProduct(array $data): Product
    {
        return Product::query()->create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh(['category', 'supplier', 'unit']);
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }

    public function listCategories(): Collection
    {
        return ProductCategory::query()->get();
    }

    public function createCategory(array $data): ProductCategory
    {
        return ProductCategory::query()->create($data);
    }
}
