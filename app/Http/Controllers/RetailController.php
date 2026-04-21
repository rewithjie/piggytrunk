<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Raiser;
use App\Models\RetailProduct;
use App\Models\RetailTransaction;
use App\Services\InventoryCatalogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RetailController extends Controller
{
    public function __construct(private readonly InventoryCatalogService $inventoryCatalog)
    {
    }

    public function index(): View
    {
        return view('pages.retail.index', [
            'pageTitle' => 'POS',
            'catalog' => $this->inventoryCatalog->getCatalog(),
            'categories' => $this->categories(),
            'transactionStatuses' => $this->transactionStatuses(),
            'transactionChannels' => $this->transactionChannels(),
            'raisers' => Raiser::query()->orderBy('name')->get(),
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
        $data = $this->filterProductColumns($this->validateProduct($request));

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('retail-products', 'public');
            $data['image'] = $data['image_path'];
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
            ->route('inventory.index')
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
        $data = $this->filterProductColumns($this->validateProduct($request));
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($record->image_path && Storage::disk('public')->exists($record->image_path)) {
                Storage::disk('public')->delete($record->image_path);
            }
            $data['image_path'] = $request->file('image')->store('retail-products', 'public');
            $data['image'] = $data['image_path'];
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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', $this->categories())],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        // Build data array based on category
        $data = [
            'name' => $validated['name'],
            'category' => $validated['category'],
            'quantity_in_stock' => $validated['stock'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'selling_price' => $validated['price'],
            'cost_price' => $validated['price'],
            // Keep legacy tier fields aligned to one value for compatibility.
            'price_per_kilo' => $validated['price'],
            'price_per_sack' => $validated['price'],
            'price_per_half_kilo' => $validated['price'],
            'price_per_quarter_kilo' => $validated['price'],
        ];

        return $data;
    }

    private function filterProductColumns(array $data): array
    {
        $columns = array_flip(Schema::getColumnListing('retail_products'));

        return array_intersect_key($data, $columns);
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
        return ['Feeds', 'Vitamins', 'Medicines', 'Others'];
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
