<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\InventoryItem;
use App\Models\RetailProduct;
use App\Models\RetailTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CashierController extends Controller
{
    public function archiveIndex(): View
    {
        $products = RetailProduct::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        $archivedProducts = $products->map(function (RetailProduct $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => 'P ' . number_format((float) $product->price, 2),
                'stock' => $product->stock,
                'description' => $product->description,
                'image' => $product->image,
                'archivedAt' => optional($product->deleted_at)?->format('F d, Y'),
            ];
        });

        return view('pages.cashier.retail.archives', [
            'archivedProducts' => $archivedProducts,
        ]);
    }

    public function createProduct(): View
    {
        return view('pages.cashier.retail.products.create', [
            'categories' => $this->categories(),
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data = [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'selling_price' => $validated['price'],
            'cost_price' => $validated['price'],
            'stock' => $validated['stock'],
            'quantity_in_stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'price_per_kilo' => $validated['price'],
            'price_per_sack' => $validated['price'],
            'price_per_half_kilo' => $validated['price'],
            'price_per_quarter_kilo' => $validated['price'],
            'code' => 'RP-' . strtoupper(uniqid()),
            'status' => 'active',
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('retail-products', 'public');
            $data['image'] = $data['image_path'];
        }

        RetailProduct::create($this->filterProductColumns($data));

        return redirect()
            ->route('cashier.inventory')
            ->with('status', 'Product added.');
    }

    public function destroyProduct(int $product): RedirectResponse
    {
        $record = RetailProduct::findOrFail($product);
        $name = $record->name;
        $record->delete();

        return redirect()
            ->route('cashier.inventory')
            ->with('status', "Product {$name} archived.");
    }

    public function restoreProduct(int $product): RedirectResponse
    {
        $record = RetailProduct::withTrashed()->findOrFail($product);
        $name = $record->name;
        $record->restore();

        return redirect()
            ->route('cashier.retail.archives')
            ->with('status', "Product {$name} restored.");
    }

    public function retailIndex(): View
    {
        $products = RetailProduct::where('status', 'active')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($product) {
                $category = $product->category === 'Growth Additives' ? 'Others' : $product->category;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $category,
                    'price' => number_format($product->selling_price, 2),
                    'rawPrice' => $product->selling_price,
                    'stock' => $product->quantity_in_stock,
                    'sales' => $product->quantity_sold ?? 0,
                    'description' => $product->description,
                    'image' => $product->image,
                ];
            });

        $categories = ['Feeds', 'Vitamins', 'Medicines', 'Others'];
        
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
        $products = RetailProduct::where('status', 'active')
            ->whereNull('deleted_at')
            ->get();

        $categories = ['Feeds', 'Vitamins', 'Medicines', 'Others'];

        $catalog = $products->map(function ($product) {
            $category = $product->category === 'Growth Additives' ? 'Others' : $product->category;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $category,
                'price' => number_format($product->selling_price, 2),
                'rawPrice' => (float) $product->selling_price,
                'stock' => $product->quantity_in_stock,
                'sales' => $product->quantity_sold ?? 0,
                'description' => $product->description,
                'image' => $product->image,
            ];
        });

        $productsByCategory = collect($categories)->mapWithKeys(function ($category) use ($catalog) {
            return [
                $category => $catalog->where('category', $category)->values(),
            ];
        });

        return view('pages.cashier.inventory.index', [
            'catalog' => $catalog,
            'productsByCategory' => $productsByCategory,
            'categories' => $categories,
        ]);
    }

    public function getRetailProducts(): JsonResponse
    {
        $products = RetailProduct::where('status', 'active')
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($product) {
                $category = $product->category === 'Growth Additives' ? 'Others' : $product->category;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $category,
                    'price' => number_format($product->selling_price, 2),
                    'rawPrice' => $product->selling_price,
                    'stock' => $product->quantity_in_stock,
                    'sales' => $product->quantity_sold ?? 0,
                    'description' => $product->description,
                    'image' => $product->image,
                ];
            });

        $categories = ['Feeds', 'Vitamins', 'Medicines', 'Others'];
        
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

    private function categories(): array
    {
        return ['Feeds', 'Vitamins', 'Medicines', 'Others'];
    }

    private function filterProductColumns(array $data): array
    {
        $columns = array_flip(Schema::getColumnListing('retail_products'));
        return array_intersect_key($data, $columns);
    }
}
