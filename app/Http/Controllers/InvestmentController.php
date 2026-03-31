<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InvestmentController extends Controller
{
    public function index(): View
    {
        $summary = [
            ['label' => 'Capital Raised', 'value' => '₱ 1,850,000'],
            ['label' => 'Active Investors', 'value' => '18'],
            ['label' => 'Projected ROI', 'value' => '18.6%'],
            ['label' => 'Next Payout', 'value' => 'April 18, 2026'],
        ];

        $batchAllocations = [
            [
                'batch' => 'BATCH-01',
                'raiser' => 'Dela Cruz Farms',
                'capital' => '₱ 650,000',
                'hog_count' => 120,
                'progress' => 78,
                'stage' => 'Fattening',
                'roi' => '19.2%',
            ],
            [
                'batch' => 'BATCH-02',
                'raiser' => 'Santos Piggery',
                'capital' => '₱ 520,000',
                'hog_count' => 96,
                'progress' => 52,
                'stage' => 'Farrowing',
                'roi' => '17.5%',
            ],
            [
                'batch' => 'BATCH-03',
                'raiser' => 'Green Meadows',
                'capital' => '₱ 430,000',
                'hog_count' => 88,
                'progress' => 36,
                'stage' => 'Piglet',
                'roi' => '16.8%',
            ],
            [
                'batch' => 'BATCH-04',
                'raiser' => 'San Pedro Livestock',
                'capital' => '₱ 250,000',
                'hog_count' => 54,
                'progress' => 64,
                'stage' => 'Farrowing',
                'roi' => '18.1%',
            ],
        ];

        $investors = [
            [
                'name' => 'Alicia Ramos',
                'tier' => 'Gold Partner',
                'committed' => '₱ 300,000',
                'batch' => 'BATCH-01',
                'joined' => 'January 12, 2026',
                'status' => 'Active',
            ],
            [
                'name' => 'Benjamin Cruz',
                'tier' => 'Silver Partner',
                'committed' => '₱ 150,000',
                'batch' => 'BATCH-02',
                'joined' => 'February 03, 2026',
                'status' => 'Active',
            ],
            [
                'name' => 'Catherine Lim',
                'tier' => 'Growth Partner',
                'committed' => '₱ 220,000',
                'batch' => 'BATCH-01',
                'joined' => 'February 17, 2026',
                'status' => 'Pending Release',
            ],
            [
                'name' => 'Daniel Soriano',
                'tier' => 'Gold Partner',
                'committed' => '₱ 180,000',
                'batch' => 'BATCH-03',
                'joined' => 'March 01, 2026',
                'status' => 'Active',
            ],
        ];

        $payoutTimeline = [
            [
                'title' => 'Cycle milestone release',
                'batch' => 'BATCH-01',
                'amount' => '₱ 120,000',
                'date' => 'April 18, 2026',
                'state' => 'Upcoming',
            ],
            [
                'title' => 'Investor profit distribution',
                'batch' => 'BATCH-02',
                'amount' => '₱ 86,500',
                'date' => 'April 28, 2026',
                'state' => 'Scheduled',
            ],
            [
                'title' => 'Capital rollover approval',
                'batch' => 'BATCH-03',
                'amount' => '₱ 54,000',
                'date' => 'May 06, 2026',
                'state' => 'For Review',
            ],
        ];

        return view('pages.investment.index', [
            'pageTitle' => 'Investment',
            'summary' => $summary,
            'batchAllocations' => $batchAllocations,
            'investors' => $investors,
            'payoutTimeline' => $payoutTimeline,
            'capitalChart' => [
                'labels' => collect($batchAllocations)->pluck('batch'),
                'values' => collect($batchAllocations)
                    ->map(fn (array $batch) => (int) preg_replace('/[^\d]/', '', $batch['capital'])),
            ],
            'stageChart' => [
                'labels' => ['Piglet', 'Farrowing', 'Fattening'],
                'values' => [
                    collect($batchAllocations)->where('stage', 'Piglet')->count(),
                    collect($batchAllocations)->where('stage', 'Farrowing')->count(),
                    collect($batchAllocations)->where('stage', 'Fattening')->count(),
                ],
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
