<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
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

        // Fetch 3 Fattening and 2 Sow raisers for consistency with Dashboard
        $fatteningRaisers = Raiser::where('pig_type', 'Fattening')->orderBy('name')->limit(3)->get();
        $sowRaisers = Raiser::where('pig_type', 'Sow')->orderBy('name')->limit(2)->get();
        $raisers = $fatteningRaisers->concat($sowRaisers);
        
        // Build batch allocations from database raisers
        $batchAllocations = [];
        $hogCounts = [120, 96, 88, 54, 70];
        $capitals = ['₱ 650,000', '₱ 520,000', '₱ 430,000', '₱ 250,000', '₱ 350,000'];
        $progressValues = [78, 52, 36, 64, 45];
        
        foreach ($raisers as $index => $raiser) {
            // Get the in-progress lifecycle stage for display (like Dashboard)
            $lifecycleStages = $this->getLifecycleCategories($raiser->pig_type);
            $inProgressStage = collect($lifecycleStages)
                ->firstWhere('status', 'in-progress');
            $stageLabel = $inProgressStage['label'] ?? 'Grower';
            
            $batchAllocations[] = [
                'batch' => 'BATCH-' . str_pad(($index + 1), 2, '0', STR_PAD_LEFT),
                'raiser' => $raiser->name,
                'capital' => $capitals[$index] ?? '₱ 0',
                'hog_count' => $hogCounts[$index] ?? 0,
                'progress' => $progressValues[$index] ?? 0,
                'stage' => $stageLabel,
                'hog_type' => $raiser->pig_type ?? 'Piglet',
            ];
        }

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

    public function create(): View
    {
        $raisers = \App\Models\Raiser::all();
        return view('pages.investment.create', [
            'raisers' => $raisers,
            'pageTitle' => 'Create New Investment'
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'raiser_id' => 'required|exists:raisers,id',
            'initial_capital' => 'required|numeric|min:0',
            'hog_type' => 'required|string',
            'total_hog' => 'required|integer|min:1',
            'investment_date' => 'required|date',
            'hog_stage' => 'nullable|string',
            'roi' => 'nullable|numeric|min:0',
        ]);

        // Store investment (you can adjust based on your Investment model structure)
        // For now, this is a placeholder - adjust based on your actual model
        
        return redirect()->route('investments.index')->with('success', 'Investment created successfully!');
    }

    private function user(): array
    {
        return [
            'name' => 'De Luna Admin',
            'role' => 'System Administrator',
            'initials' => 'DL',
        ];
    }

    private function getLifecycleCategories(string $pigType): array
    {
        $lifecycles = [
            'Sow' => [
                [
                    'label' => 'Booster',
                    'duration' => 'Initial boost',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Pre-Starter',
                    'duration' => '1 month & 2 weeks',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Starter',
                    'duration' => '2 months & 2 weeks',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Grower',
                    'duration' => '4 months - 8 months',
                    'status' => 'in-progress',
                ],
                [
                    'label' => 'Gilt Developer',
                    'duration' => 'Development stage',
                    'status' => 'pending',
                ],
                [
                    'label' => 'Gestation Feed',
                    'duration' => 'Pregnancy period',
                    'status' => 'pending',
                ],
                [
                    'label' => 'Lactation Feed',
                    'duration' => 'Nursing stage',
                    'status' => 'pending',
                ],
                [
                    'label' => 'Separation',
                    'duration' => 'Final Stage',
                    'status' => 'pending',
                ],
            ],
            'Fattening' => [
                [
                    'label' => 'Booster',
                    'duration' => 'Initial boost',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Pre-Starter',
                    'duration' => '1 month & 2 weeks',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Starter',
                    'duration' => '2 months & 2 weeks',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Grower',
                    'duration' => '2 months & 2 weeks',
                    'status' => 'in-progress',
                ],
                [
                    'label' => 'Finisher',
                    'duration' => 'Final growth stage',
                    'status' => 'pending',
                ],
                [
                    'label' => 'Selling',
                    'duration' => 'Final Stage',
                    'status' => 'pending',
                ],
            ],
        ];

        return $lifecycles[$pigType] ?? $lifecycles['Fattening'];
    }
}
