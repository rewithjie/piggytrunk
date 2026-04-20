<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\InventoryItem;
use App\Models\RetailProduct;
use App\Models\RetailTransaction;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CashierController extends Controller
{
    public function retailIndex(): View
    {
        $products = RetailProduct::where('status', 'active')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => number_format($product->selling_price, 2),
                    'rawPrice' => $product->selling_price,
                    'stock' => $product->quantity_in_stock,
                    'sales' => $product->quantity_sold ?? 0,
                    'description' => $product->description,
                    'image' => $product->image,
                ];
            });

        $categories = ['Feeds', 'Vitamins', 'Medicines', 'Growth Additives'];
        
        $productsByCategory = collect($categories)->mapWithKeys(function ($category) use ($products) {
            return [
                $category => $products->where('category', $category)->values(),
            ];
        });

        $raisers = \App\Models\Raiser::where('status', 'active')->get();
        
        // Get recent transactions for this cashier
        $transactions = RetailTransaction::query()
            ->with(['product', 'raiser'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $orders = $transactions->map(function (RetailTransaction $order) {
            $customer = $order->raiser?->name ?: $order->customer_name ?: 'Walk-in';

            return [
                'type' => 'transaction',
                'id' => $order->id,
                'customer' => $customer,
                'items' => ($order->product?->name ?? 'Unknown Product') . ' x' . $order->quantity,
                'channel' => $order->channel,
                'total' => number_format((float) $order->total_amount, 2),
                'status' => $order->status,
                'date' => Carbon::parse($order->created_at)->format('F d, Y'),
            ];
        });

        // Fetch recent activities (product add/delete from admin)
        $activities = ActivityLog::where('log_name', 'retail')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function (ActivityLog $activity) {
                return [
                    'type' => 'activity',
                    'description' => $activity->description,
                    'date' => Carbon::parse($activity->created_at)->format('F d, Y'),
                    'time' => Carbon::parse($activity->created_at)->format('g:i A'),
                ];
            });

        return view('pages.cashier.retail.index', [
            'productsByCategory' => $productsByCategory,
            'categories' => $categories,
            'raisers' => $raisers,
            'orders' => $orders,
            'activities' => $activities,
        ]);
    }

    public function inventoryIndex(): View
    {
        $items = InventoryItem::where('status', 'active')
            ->with('category')
            ->get();

        $categories = $items->pluck('category.name')->unique()->values();
        
        $itemsByCategory = collect($categories)->mapWithKeys(function ($category) use ($items) {
            return [
                $category => $items->where('category.name', $category)->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'category' => $item->category->name ?? 'Uncategorized',
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'description' => $item->description,
                    ];
                }),
            ];
        });

        return view('pages.cashier.inventory.index', [
            'itemsByCategory' => $itemsByCategory,
            'categories' => $categories,
        ]);
    }

    public function getRetailProducts(): JsonResponse
    {
        $products = RetailProduct::where('status', 'active')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'price' => number_format($product->selling_price, 2),
                    'rawPrice' => $product->selling_price,
                    'stock' => $product->quantity_in_stock,
                    'sales' => $product->quantity_sold ?? 0,
                    'description' => $product->description,
                    'image' => $product->image,
                ];
            });

        $categories = ['Feeds', 'Vitamins', 'Medicines', 'Growth Additives'];
        
        $productsByCategory = collect($categories)->mapWithKeys(function ($category) use ($products) {
            return [
                $category => $products->where('category', $category)->values(),
            ];
        });

        return response()->json([
            'success' => true,
            'productsByCategory' => $productsByCategory,
            'categories' => $categories,
        ]);
    }

    public function getInventoryItems(): JsonResponse
    {
        $items = InventoryItem::where('status', 'active')
            ->with('category')
            ->get();

        $categories = $items->pluck('category.name')->unique()->values();
        
        $itemsByCategory = collect($categories)->mapWithKeys(function ($category) use ($items) {
            return [
                $category => $items->where('category.name', $category)->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'category' => $item->category->name ?? 'Uncategorized',
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'description' => $item->description,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'itemsByCategory' => $itemsByCategory,
            'categories' => $categories,
        ]);
    }
}
