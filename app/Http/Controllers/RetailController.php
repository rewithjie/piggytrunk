<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class RetailController extends Controller
{
    public function index(): View
    {
        $summary = [
            ['label' => 'Today Sales', 'value' => 'PHP 18,940'],
            ['label' => 'Orders Processed', 'value' => '27'],
            ['label' => 'Best Seller', 'value' => 'Premium Hog Feed'],
            ['label' => 'Low Stock Alerts', 'value' => '3 items'],
        ];

        $catalog = [
            [
                'name' => 'Premium Hog Feed',
                'category' => 'Feeds',
                'price' => 'PHP 1,250',
                'stock' => 42,
                'status' => 'In Stock',
                'sales' => '98 bags',
            ],
            [
                'name' => 'Piglet Starter Mix',
                'category' => 'Feeds',
                'price' => 'PHP 930',
                'stock' => 12,
                'status' => 'Low Stock',
                'sales' => '74 packs',
            ],
            [
                'name' => 'Growth Booster Vitamins',
                'category' => 'Vitamins',
                'price' => 'PHP 380',
                'stock' => 58,
                'status' => 'In Stock',
                'sales' => '66 bottles',
            ],
            [
                'name' => 'Deworming Medicine',
                'category' => 'Medicines',
                'price' => 'PHP 540',
                'stock' => 9,
                'status' => 'Low Stock',
                'sales' => '29 boxes',
            ],
        ];

        $orders = [
            [
                'customer' => 'Maria Santos',
                'items' => 'Premium Hog Feed x3',
                'channel' => 'Walk-in',
                'total' => 'PHP 3,750',
                'status' => 'Completed',
                'date' => 'March 27, 2026',
            ],
            [
                'customer' => 'Jose Ramirez',
                'items' => 'Starter Mix x2, Vitamins x1',
                'channel' => 'Facebook Shop',
                'total' => 'PHP 2,240',
                'status' => 'Packed',
                'date' => 'March 27, 2026',
            ],
            [
                'customer' => 'Ana Villanueva',
                'items' => 'Deworming Medicine x1',
                'channel' => 'Walk-in',
                'total' => 'PHP 540',
                'status' => 'Completed',
                'date' => 'March 26, 2026',
            ],
            [
                'customer' => 'Paolo Cruz',
                'items' => 'Premium Hog Feed x1, Vitamins x2',
                'channel' => 'Messenger',
                'total' => 'PHP 2,010',
                'status' => 'For Delivery',
                'date' => 'March 26, 2026',
            ],
        ];

        $channels = [
            ['label' => 'Walk-in', 'value' => 44],
            ['label' => 'Facebook Shop', 'value' => 31],
            ['label' => 'Messenger', 'value' => 25],
        ];

        $topSellers = [
            ['name' => 'Premium Hog Feed', 'category' => 'Feeds', 'sold' => 98, 'share' => 86],
            ['name' => 'Piglet Starter Mix', 'category' => 'Feeds', 'sold' => 74, 'share' => 64],
            ['name' => 'Growth Booster Vitamins', 'category' => 'Vitamins', 'sold' => 66, 'share' => 58],
        ];

        return view('pages.retail.index', [
            'pageTitle' => 'Retail Shop',
            'hideTopbarTitle' => true,
            'summary' => $summary,
            'catalog' => $catalog,
            'orders' => $orders,
            'channels' => $channels,
            'topSellers' => $topSellers,
            'salesChart' => [
                'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'values' => [12400, 16800, 14350, 17100, 18940, 16220, 15480],
            ],
            'channelChart' => [
                'labels' => collect($channels)->pluck('label'),
                'values' => collect($channels)->pluck('value'),
            ],
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
}
