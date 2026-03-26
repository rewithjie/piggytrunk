<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->toString();

        $stats = [
            [
                'label' => 'Investment Amount',
                'value' => 'PHP 0.00',
            ],
            [
                'label' => 'Batch Name',
                'value' => 'BATCH-01',
            ],
            [
                'label' => 'Hog Cycles',
                'cycles' => [
                    ['label' => 'Piglet', 'value' => '0'],
                    ['label' => 'Farrowing', 'value' => '0'],
                    ['label' => 'Fattening', 'value' => '0'],
                ],
            ],
            [
                'label' => 'Hog Expenses Amount',
                'value' => 'PHP 0.00',
            ],
            [
                'label' => 'Expected Profit Return',
                'value' => 'PHP 0.00',
            ],
        ];

        $liveFeed = [
            ['label' => 'Piglet Stage', 'count' => '20 / 40 Hogs', 'width' => 50],
            ['label' => 'Farrowing Stage', 'count' => '12 / 40 Hogs', 'width' => 30],
            ['label' => 'Fattening Stage', 'count' => '8 / 40 Hogs', 'width' => 20],
        ];

        $raisers = Raiser::query()
            ->when($query !== '', function ($db) use ($query) {
                $db->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('code', 'like', "%{$query}%")
                        ->orWhere('location', 'like', "%{$query}%")
                        ->orWhere('batch', 'like', "%{$query}%")
                        ->orWhere('pig_type', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%");
                });
            })
            ->orderBy('name')
            ->get();

        $statusChart = $raisers
            ->groupBy(fn (Raiser $raiser) => $raiser->status)
            ->map(fn ($group) => $group->count());

        $stageChart = collect($liveFeed)->map(function (array $item) {
            return [
                'label' => str_replace(' Stage', '', $item['label']),
                'value' => (int) explode('/', $item['count'])[0],
            ];
        });

        return view('pages.dashboard', [
            'pageTitle' => 'Dashboard',
            'stats' => $stats,
            'raisers' => $raisers,
            'liveFeed' => $liveFeed,
            'statusChart' => [
                'labels' => $statusChart->keys()->values(),
                'values' => $statusChart->values(),
            ],
            'stageChart' => [
                'labels' => $stageChart->pluck('label'),
                'values' => $stageChart->pluck('value'),
            ],
            'query' => $query,
            'user' => [
                'name' => 'De Luna Admin',
                'role' => 'System Administrator',
                'initials' => 'DL',
            ],
        ]);
    }
}
