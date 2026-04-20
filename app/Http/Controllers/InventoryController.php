<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        return view('pages.inventory.index', [
            'pageTitle' => 'Inventory',
            'items' => $this->items(),
            'user' => $this->user(),
        ]);
    }

    public function create(): View
    {
        return view('pages.inventory.create', [
            'pageTitle' => 'Add Inventory Item',
            'user' => $this->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:50'],
            'cost' => ['required', 'numeric', 'min:0'],
            'supplier' => ['required', 'string', 'max:120'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $items = $this->items();
        $nextId = empty($items) ? 1 : (max(array_column($items, 'id')) + 1);

        // Determine stock status
        $stockStatus = 'in-stock';
        if ($validated['stock'] <= 50) {
            $stockStatus = 'restock';
        } elseif ($validated['stock'] <= 100) {
            $stockStatus = 'low-stock';
        }

        $items[] = [
            'id' => $nextId,
            'name' => trim($validated['name']),
            'category' => strtoupper(trim($validated['category'])),
            'price' => '₱' . number_format((float) $validated['cost'], 2),
            'cost' => (float) $validated['cost'],
            'supplier' => trim($validated['supplier']),
            'stock' => (int) $validated['stock'],
            'unit' => 'units',
            'status' => $stockStatus,
        ];

        $request->session()->put('inventory_items', $items);

        return redirect()
            ->route('inventory.index')
            ->with('status', 'Inventory item added successfully.');
    }

    private function items(): array
    {
        $baseItems = [];

        $sessionItems = session('inventory_items', []);

        if (!is_array($sessionItems)) {
            return $baseItems;
        }

        return $sessionItems !== [] ? $sessionItems : $baseItems;
    }

    private function user(): array
    {
        return [
            'name' => 'De Luna Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }

    private function productImage(string $label, string $background, string $accent): string
    {
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="320" height="220" viewBox="0 0 320 220">
    <rect width="320" height="220" rx="28" fill="{$background}"/>
    <circle cx="76" cy="72" r="30" fill="{$accent}" opacity="0.15"/>
    <circle cx="250" cy="60" r="22" fill="{$accent}" opacity="0.12"/>
    <rect x="52" y="92" width="216" height="74" rx="20" fill="#ffffff" opacity="0.92"/>
    <rect x="82" y="112" width="156" height="12" rx="6" fill="{$accent}" opacity="0.9"/>
    <rect x="104" y="134" width="112" height="12" rx="6" fill="{$accent}" opacity="0.45"/>
    <text x="160" y="188" text-anchor="middle" font-family="Arial, sans-serif" font-size="28" font-weight="700" fill="{$accent}">{$label}</text>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,'.rawurlencode($svg);
    }
}
