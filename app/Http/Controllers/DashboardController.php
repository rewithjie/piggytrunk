<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim($request->string('q')->toString());

        $stats = [
            [
                'label' => 'Total Active Investment',
                'value' => '₱ ' . $this->formatCompactNumber(2450000),
            ],
            [
                'label' => 'Number of Hog Batch',
                'value' => '5',
            ],
            [
                'label' => 'Investment Allocation',
                'cycles' => [
                    ['label' => 'Fattening', 'value' => '₱ ' . $this->formatCompactNumber(1500000)],
                    ['label' => 'Sow', 'value' => '₱ ' . $this->formatCompactNumber(650000)],
                    ['label' => 'Boar', 'value' => '₱ ' . $this->formatCompactNumber(300000)],
                ],
            ],
            [
                'label' => 'Total Capital Invested',
                'value' => '₱ ' . $this->formatCompactNumber(2450000),
            ],
            [
                'label' => 'Expected Profit Return',
                'value' => '₱ ' . $this->formatCompactNumber(1225000) . ' (50%)',
            ],
        ];

        $liveFeed = [
            ['label' => 'Piglet Stage', 'count' => '20 / 40 Hogs', 'width' => 50],
            ['label' => 'Farrowing Stage', 'count' => '12 / 40 Hogs', 'width' => 30],
            ['label' => 'Fattening Stage', 'count' => '8 / 40 Hogs', 'width' => 20],
        ];

        $raisersQuery = Raiser::query()
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
            ->orderBy('name');

        $raisers = (clone $raisersQuery)
            ->paginate(8)
            ->withQueryString();

        $statusChart = (clone $raisersQuery)
            ->get()
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
                'labels' => $statusChart->keys()->values()->all(),
                'values' => $statusChart->values()->all(),
            ],
            'stageChart' => [
                'labels' => $stageChart->pluck('label')->all(),
                'values' => $stageChart->pluck('value')->all(),
            ],
            'query' => $query,
            'user' => [
                'name' => 'De Luna Admin',
                'role' => 'System Administrator',
                'initials' => 'DL',
            ],
        ]);
    }

    private function formatCompactNumber(int $amount): string
    {
        return number_format($amount);
    }
}
