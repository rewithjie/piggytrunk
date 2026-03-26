<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(): View
    {
        $items = [
            [
                'name' => 'Premium Hog Feed',
                'category' => 'Feeds',
                'price' => 'PHP 1,250.00',
                'stock' => 42,
                'unit' => 'bags',
                'raiser' => 'Dela Cruz Farms',
                'image' => $this->productImage('Feed', '#ffe3e8', '#ff6b81'),
            ],
            [
                'name' => 'Growth Booster Vitamins',
                'category' => 'Vitamins',
                'price' => 'PHP 380.00',
                'stock' => 58,
                'unit' => 'bottles',
                'raiser' => 'Santos Piggery',
                'image' => $this->productImage('Vit', '#e5f1ff', '#4a8ef0'),
            ],
            [
                'name' => 'Deworming Medicine',
                'category' => 'Medicines',
                'price' => 'PHP 540.00',
                'stock' => 21,
                'unit' => 'boxes',
                'raiser' => 'Green Meadows',
                'image' => $this->productImage('Med', '#e6f8ef', '#30b36d'),
            ],
            [
                'name' => 'Piglet Starter Mix',
                'category' => 'Feeds',
                'price' => 'PHP 930.00',
                'stock' => 35,
                'unit' => 'packs',
                'raiser' => 'Dela Cruz Farms',
                'image' => $this->productImage('Mix', '#fff2db', '#ff9a3d'),
            ],
        ];

        $purchases = [
            [
                'customer' => 'Maria Santos',
                'item' => 'Premium Hog Feed',
                'quantity' => '3 bags',
                'price' => 'PHP 3,750.00',
                'source' => 'Dela Cruz Farms',
                'date' => 'March 26, 2026',
            ],
            [
                'customer' => 'Jose Ramirez',
                'item' => 'Growth Booster Vitamins',
                'quantity' => '2 bottles',
                'price' => 'PHP 760.00',
                'source' => 'Santos Piggery',
                'date' => 'March 25, 2026',
            ],
            [
                'customer' => 'Ana Villanueva',
                'item' => 'Deworming Medicine',
                'quantity' => '1 box',
                'price' => 'PHP 540.00',
                'source' => 'Green Meadows',
                'date' => 'March 25, 2026',
            ],
            [
                'customer' => 'Paolo Cruz',
                'item' => 'Piglet Starter Mix',
                'quantity' => '4 packs',
                'price' => 'PHP 3,720.00',
                'source' => 'Dela Cruz Farms',
                'date' => 'March 24, 2026',
            ],
        ];

        $raiserInventory = [
            [
                'raiser' => 'Dela Cruz Farms',
                'item' => 'Premium Hog Feed',
                'category' => 'Feeds',
                'opening' => 50,
                'sold' => 8,
                'remaining' => 42,
            ],
            [
                'raiser' => 'Santos Piggery',
                'item' => 'Growth Booster Vitamins',
                'category' => 'Vitamins',
                'opening' => 60,
                'sold' => 2,
                'remaining' => 58,
            ],
            [
                'raiser' => 'Green Meadows',
                'item' => 'Deworming Medicine',
                'category' => 'Medicines',
                'opening' => 22,
                'sold' => 1,
                'remaining' => 21,
            ],
            [
                'raiser' => 'Dela Cruz Farms',
                'item' => 'Piglet Starter Mix',
                'category' => 'Feeds',
                'opening' => 39,
                'sold' => 4,
                'remaining' => 35,
            ],
        ];

        $soldFromStock = [
            [
                'customer' => 'Maria Santos',
                'raiser' => 'Dela Cruz Farms',
                'item' => 'Premium Hog Feed',
                'category' => 'Feeds',
                'quantity' => '3 bags',
                'sold_at' => 'March 26, 2026',
            ],
            [
                'customer' => 'Jose Ramirez',
                'raiser' => 'Santos Piggery',
                'item' => 'Growth Booster Vitamins',
                'category' => 'Vitamins',
                'quantity' => '2 bottles',
                'sold_at' => 'March 25, 2026',
            ],
            [
                'customer' => 'Ana Villanueva',
                'raiser' => 'Green Meadows',
                'item' => 'Deworming Medicine',
                'category' => 'Medicines',
                'quantity' => '1 box',
                'sold_at' => 'March 25, 2026',
            ],
            [
                'customer' => 'Paolo Cruz',
                'raiser' => 'Dela Cruz Farms',
                'item' => 'Piglet Starter Mix',
                'category' => 'Feeds',
                'quantity' => '4 packs',
                'sold_at' => 'March 24, 2026',
            ],
        ];

        return view('pages.inventory.index', [
            'pageTitle' => 'Inventory',
            'items' => $items,
            'purchases' => $purchases,
            'raiserInventory' => $raiserInventory,
            'soldFromStock' => $soldFromStock,
            'user' => $this->user(),
        ]);
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
