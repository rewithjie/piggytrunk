<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Raiser;
use App\Models\RetailProduct;
use App\Models\RetailTransaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RetailController extends Controller
{
    public function index(): View
    {
        $products = RetailProduct::query()->whereNull('deleted_at')->orderBy('name')->get();
        $transactions = RetailTransaction::query()
            ->with(['product', 'raiser'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $today = Carbon::today();
        $todayTransactions = $transactions->filter(
            fn (RetailTransaction $row) => Carbon::parse($row->created_at)->isSameDay($today)
        );
        $monthStart = Carbon::today()->startOfMonth();

        $salesByProduct = $transactions
            ->groupBy('retail_product_id')
            ->map(fn ($group) => (int) $group->sum('quantity'));

        $monthlySalesByProduct = $transactions
            ->filter(fn (RetailTransaction $row) => Carbon::parse($row->created_at)->between($monthStart, $today))
            ->groupBy('retail_product_id')
            ->map(fn ($group) => (int) $group->sum('quantity'));

        $bestSellerId = $salesByProduct->sortDesc()->keys()->first();
        $bestSeller = $bestSellerId ? $products->firstWhere('id', $bestSellerId)?->name : null;

        $summary = [
            ['label' => 'Today Sales', 'value' => $this->formatCurrency((float) $todayTransactions->sum('total_amount'))],
            ['label' => 'Orders Processed', 'value' => (string) $todayTransactions->count()],
            ['label' => 'Best Seller', 'value' => $bestSeller ?? 'No sales yet'],
            ['label' => 'Low Stock Alerts', 'value' => $products->where('stock', '<=', 10)->count() . ' items'],
        ];

        $catalog = $products->map(function (RetailProduct $product) use ($monthlySalesByProduct) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => $this->formatCurrency((float) $product->price),
                'stock' => $product->stock,
                'status' => $product->stock_status,
                'sales' => ($monthlySalesByProduct[$product->id] ?? 0) . ' units',
                'description' => $product->description,
                'image' => $product->image,
                'rawPrice' => (float) $product->price,
                'price_per_kilo' => (float) $product->price_per_kilo,
                'price_per_half_kilo' => (float) $product->price_per_half_kilo,
                'price_per_quarter_kilo' => (float) $product->price_per_quarter_kilo,
                'price_per_sack' => (float) $product->price_per_sack,
            ];
        });

        $orders = $transactions->map(function (RetailTransaction $order) {
            $customer = $order->raiser?->name ?: $order->customer_name ?: 'Walk-in';

            return [
                'id' => $order->id,
                'customer' => $customer,
                'items' => ($order->product?->name ?? 'Unknown Product') . ' x' . $order->quantity,
                'channel' => $order->channel,
                'total' => $this->formatCurrency((float) $order->total_amount),
                'status' => $order->status,
                'date' => Carbon::parse($order->created_at)->format('F d, Y'),
            ];
        });

        // Fetch recent activities (product add/delete)
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

        // Merge activities and orders, sorted by date
        $recentItems = collect([
            ...$orders->map(fn ($order) => [...$order, 'type' => 'transaction']),
            ...$activities,
        ])->sortByDesc(fn ($item) => $item['date'] ?? '')->take(10);

        $channelStats = $transactions
            ->groupBy('channel')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        $totalChannel = max(1, $channelStats->sum());
        $channels = $channelStats->map(fn ($value, $label) => [
            'label' => $label,
            'value' => (int) round(($value / $totalChannel) * 100),
        ])->values();

        $topSellersBase = $salesByProduct
            ->sortDesc()
            ->take(3)
            ->map(function ($sold, $productId) use ($products) {
                $product = $products->firstWhere('id', $productId);

                return [
                    'name' => $product?->name ?? 'Unknown Product',
                    'category' => $product?->category ?? 'N/A',
                    'sold' => (int) $sold,
                ];
            })->values();

        $maxSold = max(1, (int) $topSellersBase->max('sold'));
        $topSellers = $topSellersBase->map(fn ($item) => [
            'name' => $item['name'],
            'category' => $item['category'],
            'sold' => $item['sold'],
            'share' => (int) round(($item['sold'] / $maxSold) * 100),
        ]);

        $salesChartLabels = [];
        $salesChartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $salesChartLabels[] = $date->format('D');
            $salesChartValues[] = (float) $transactions
                ->where('transaction_date', $date->toDateString())
                ->sum('total_amount');
        }

        return view('pages.retail.index', [
            'pageTitle' => 'POS',
            'summary' => $summary,
            'catalog' => $catalog,
            'orders' => $orders,
            'activities' => $activities,
            'recentItems' => $recentItems,
            'channels' => $channels,
            'topSellers' => $topSellers,
            'categories' => $this->categories(),
            'transactionStatuses' => $this->transactionStatuses(),
            'transactionChannels' => $this->transactionChannels(),
            'raisers' => Raiser::query()->orderBy('name')->get(),
            'salesChart' => [
                'labels' => $salesChartLabels,
                'values' => $salesChartValues,
            ],
            'channelChart' => [
                'labels' => collect($channels)->pluck('label'),
                'values' => collect($channels)->pluck('value'),
            ],
            'user' => $this->user(),
        ]);
    }

    public function archiveIndex(): View
    {
        $products = RetailProduct::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        $archivedProducts = $products->map(function (RetailProduct $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'price' => $this->formatCurrency((float) $product->price),
                'stock' => $product->stock,
                'description' => $product->description,
                'image' => $product->image,
                'archivedAt' => $product->deleted_at->format('F d, Y'),
            ];
        });

        return view('pages.retail.archives', [
            'pageTitle' => 'Archived Products',
            'archivedProducts' => $archivedProducts,
            'categories' => $this->categories(),
            'user' => $this->user(),
        ]);
    }

    public function createProduct(): View
    {
        return view('pages.retail.products.create', [
            'pageTitle' => 'Add Retail Product',
            'categories' => $this->categories(),
            'user' => $this->user(),
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('retail-products', 'public');
        }

        // Generate code
        $data['code'] = 'RP-' . strtoupper(uniqid());
        $data['status'] = 'active';

        $product = RetailProduct::create($data);

        // Log the activity
        ActivityLog::create([
            'log_name' => 'retail',
            'description' => "Added product: {$product->name}",
            'subject_type' => RetailProduct::class,
            'subject_id' => $product->id,
            'causer_type' => 'admin',
            'causer_id' => auth()->id(),
            'created_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('retail.index')
            ->with('status', "Product {$product->name} added.");
    }

    public function editProduct(int $product): View
    {
        $record = RetailProduct::findOrFail($product);

        return view('pages.retail.products.edit', [
            'pageTitle' => 'Edit Retail Product',
            'product' => $record,
            'categories' => $this->categories(),
            'user' => $this->user(),
        ]);
    }

    public function updateProduct(Request $request, int $product): RedirectResponse
    {
        $record = RetailProduct::findOrFail($product);
        $data = $this->validateProduct($request);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($record->image_path && Storage::disk('public')->exists($record->image_path)) {
                Storage::disk('public')->delete($record->image_path);
            }
            $data['image_path'] = $request->file('image')->store('retail-products', 'public');
        }

        $record->update($data);

        return redirect()
            ->route('retail.index')
            ->with('status', "Product {$record->name} updated.");
    }

    public function destroyProduct(int $product): RedirectResponse
    {
        $record = RetailProduct::findOrFail($product);
        $name = $record->name;
        
        // Archive product using soft delete
        $record->delete();

        // Log the activity
        ActivityLog::create([
            'log_name' => 'retail',
            'description' => "Archived product: {$name}",
            'subject_type' => RetailProduct::class,
            'subject_id' => $product,
            'causer_type' => 'admin',
            'causer_id' => auth()->id(),
            'created_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('retail.index')
            ->with('status', "Product {$name} archived.");
    }

    public function restoreProduct(int $product): RedirectResponse
    {
        $record = RetailProduct::withTrashed()->findOrFail($product);
        $name = $record->name;
        
        // Restore product (undo soft delete)
        $record->restore();

        // Log the activity
        ActivityLog::create([
            'log_name' => 'retail',
            'description' => "Restored product: {$name}",
            'subject_type' => RetailProduct::class,
            'subject_id' => $product,
            'causer_type' => 'admin',
            'causer_id' => auth()->id(),
            'created_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('retail.archives')
            ->with('status', "Product {$name} restored.");
    }

    public function createTransaction(): View
    {
        return view('pages.retail.transactions.create', [
            'pageTitle' => 'Add Retail Transaction',
            'products' => RetailProduct::query()->orderBy('name')->get(),
            'raisers' => Raiser::query()->orderBy('name')->get(),
            'transactionStatuses' => $this->transactionStatuses(),
            'transactionChannels' => $this->transactionChannels(),
            'user' => $this->user(),
        ]);
    }

    public function storeTransaction(Request $request): RedirectResponse
    {
        $payload = $this->validateTransaction($request);
        $product = RetailProduct::findOrFail($payload['retail_product_id']);
        $payload['total_amount'] = $product->price * $payload['quantity'];

        RetailTransaction::create($payload);

        return redirect()
            ->route('retail.index')
            ->with('status', 'Retail transaction added.');
    }

    public function editTransaction(int $transaction): View
    {
        $record = RetailTransaction::findOrFail($transaction);

        return view('pages.retail.transactions.edit', [
            'pageTitle' => 'Edit Retail Transaction',
            'transaction' => $record,
            'products' => RetailProduct::query()->orderBy('name')->get(),
            'raisers' => Raiser::query()->orderBy('name')->get(),
            'transactionStatuses' => $this->transactionStatuses(),
            'transactionChannels' => $this->transactionChannels(),
            'user' => $this->user(),
        ]);
    }

    public function updateTransaction(Request $request, int $transaction): RedirectResponse
    {
        $record = RetailTransaction::findOrFail($transaction);
        $payload = $this->validateTransaction($request);
        $product = RetailProduct::findOrFail($payload['retail_product_id']);
        $payload['total_amount'] = $product->price * $payload['quantity'];
        $record->update($payload);

        return redirect()
            ->route('retail.index')
            ->with('status', 'Retail transaction updated.');
    }

    public function destroyTransaction(int $transaction): RedirectResponse
    {
        $record = RetailTransaction::findOrFail($transaction);
        $record->delete();

        return redirect()
            ->route('retail.index')
            ->with('status', 'Retail transaction deleted.');
    }

    private function validateProduct(Request $request): array
    {
        $category = $request->input('category');
        
        // Dynamic validation based on category
        if ($category === 'Feeds') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
                'price_per_kilo' => ['required', 'numeric', 'min:0'],
                'price_per_sack' => ['nullable', 'numeric', 'min:0'],
                'price_per_half_kilo' => ['nullable', 'numeric', 'min:0'],
                'price_per_quarter_kilo' => ['nullable', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'description' => ['nullable', 'string', 'max:1000'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]);
        } elseif ($category === 'Vitamins') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
                'price_per_bottle' => ['required', 'numeric', 'min:0'],
                'price_per_tablet' => ['nullable', 'numeric', 'min:0'],
                'price_per_vial' => ['nullable', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'description' => ['nullable', 'string', 'max:1000'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]);
        } elseif ($category === 'Medicines') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
                'price_per_bottle' => ['required', 'numeric', 'min:0'],
                'price_per_tablet' => ['nullable', 'numeric', 'min:0'],
                'price_per_injection' => ['nullable', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'description' => ['nullable', 'string', 'max:1000'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]);
        } elseif ($category === 'Growth Additives') {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
                'price_per_liter' => ['required', 'numeric', 'min:0'],
                'price_per_bottle' => ['nullable', 'numeric', 'min:0'],
                'price_per_sachet' => ['nullable', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'description' => ['nullable', 'string', 'max:1000'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            ]);
        }

        // Build data array based on category
        $data = [
            'name' => $request->input('name'),
            'category' => $category,
            'quantity_in_stock' => $request->input('stock'),
            'description' => $request->input('description'),
        ];

        // Add category-specific pricing
        if ($category === 'Feeds') {
            $data['price_per_kilo'] = $request->input('price_per_kilo');
            $data['price_per_sack'] = $request->input('price_per_sack');
            $data['price_per_half_kilo'] = $request->input('price_per_half_kilo');
            $data['price_per_quarter_kilo'] = $request->input('price_per_quarter_kilo');
            $data['selling_price'] = $request->input('price_per_kilo');
        } elseif ($category === 'Vitamins') {
            $data['price_per_kilo'] = $request->input('price_per_bottle');
            $data['price_per_sack'] = $request->input('price_per_tablet');
            $data['price_per_half_kilo'] = $request->input('price_per_vial');
            $data['selling_price'] = $request->input('price_per_bottle');
        } elseif ($category === 'Medicines') {
            $data['price_per_kilo'] = $request->input('price_per_bottle');
            $data['price_per_sack'] = $request->input('price_per_tablet');
            $data['price_per_half_kilo'] = $request->input('price_per_injection');
            $data['selling_price'] = $request->input('price_per_bottle');
        } elseif ($category === 'Growth Additives') {
            $data['price_per_kilo'] = $request->input('price_per_liter');
            $data['price_per_sack'] = $request->input('price_per_bottle');
            $data['price_per_half_kilo'] = $request->input('price_per_sachet');
            $data['selling_price'] = $request->input('price_per_liter');
        }

        return $data;
    }

    private function validateTransaction(Request $request): array
    {
        return $request->validate([
            'retail_product_id' => ['required', 'integer', 'exists:retail_products,id'],
            'raiser_id' => ['nullable', 'integer', 'exists:raisers,id'],
            'customer_name' => ['nullable', 'string', 'max:255', 'required_without:raiser_id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'channel' => ['required', 'string', 'in:' . implode(',', $this->transactionChannels())],
            'status' => ['required', 'string', 'in:' . implode(',', $this->transactionStatuses())],
            'transaction_date' => ['required', 'date'],
        ]);
    }

    private function categories(): array
    {
        return ['Feeds', 'Vitamins', 'Medicines', 'Growth Additives'];
    }

    private function transactionStatuses(): array
    {
        return ['Completed', 'Packed', 'For Delivery', 'Cancelled'];
    }

    private function transactionChannels(): array
    {
        return ['Walk-in', 'Facebook Shop', 'Messenger'];
    }

    private function formatCurrency(float $value): string
    {
        return '₱ ' . number_format($value, 2);
    }

    private function user(): array
    {
        return [
            'name' => 'De Luna Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }
}
