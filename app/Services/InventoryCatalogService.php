<?php

namespace App\Services;

use App\Models\RetailProduct;
use App\Models\RetailTransaction;
use Illuminate\Support\Collection;

class InventoryCatalogService
{
    public function getCatalog(): Collection
    {
        $products = RetailProduct::query()->orderBy('name')->get();
        $salesByProduct = RetailTransaction::query()
            ->selectRaw('retail_product_id, COALESCE(SUM(quantity), 0) as total_sold')
            ->groupBy('retail_product_id')
            ->pluck('total_sold', 'retail_product_id');

        return $products->map(function (RetailProduct $product) use ($salesByProduct) {
            $category = $product->category === 'Growth Additives' ? 'Others' : ($product->category ?? 'Feeds');

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $category,
                'price' => $this->formatCurrency((float) $product->price),
                'stock' => $product->stock,
                'status' => $product->stock_status,
                'sales' => ($salesByProduct[$product->id] ?? 0) . ' units',
                'description' => $product->description,
                'image' => $product->image,
                'rawPrice' => (float) $product->price,
                'price_per_kilo' => (float) $product->price_per_kilo,
                'price_per_half_kilo' => (float) $product->price_per_half_kilo,
                'price_per_quarter_kilo' => (float) $product->price_per_quarter_kilo,
                'price_per_sack' => (float) $product->price_per_sack,
            ];
        })->values();
    }

    private function formatCurrency(float $value): string
    {
        return 'P ' . number_format($value, 2);
    }
}
