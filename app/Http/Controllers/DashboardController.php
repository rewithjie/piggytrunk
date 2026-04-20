<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use App\Models\Batch;
use App\Models\Investment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // Fetch 3 Fattening and 2 Sow raisers for dashboard lifecycle
        $fatteningRaisers = Raiser::whereHas('pigType', function ($query) {
            $query->where('name', 'Fattening');
        })->orderBy('name')->limit(3)->get();
        
        $sowRaisers = Raiser::whereHas('pigType', function ($query) {
            $query->where('name', 'Sow');
        })->orderBy('name')->limit(2)->get();
        
        $raisers = $fatteningRaisers->concat($sowRaisers);

        // Investment Summary Data - Fetch from database
        $allInvestments = Investment::with('batch.pigType')->get();
        $totalActive = $allInvestments->where('status', 'Active')->count();
        $batchCount = Batch::count();
        
        // Calculate allocation by pig type
        $allocationFattening = 0;
        $allocationSow = 0;
        $totalCapital = 0;
        $expectedProfit = 0;
        
        foreach ($allInvestments as $investment) {
            $pigType = $investment->batch?->pigType?->name;
            $amount = (float) $investment->total_amount;
            
            $totalCapital += $amount;
            $expectedProfit += (float) ($investment->expected_profit ?? 0);
            
            if ($pigType === 'Fattening') {
                $allocationFattening += $amount;
            } elseif ($pigType === 'Sow') {
                $allocationSow += $amount;
            }
        }
        
        $investmentSummary = [
            'totalActive' => $totalActive,
            'batchCount' => $batchCount,
            'allocation' => [
                'fattening' => $allocationFattening,
                'sow' => $allocationSow,
            ],
            'totalCapital' => $totalCapital,
            'expectedProfit' => $expectedProfit,
        ];

        // Raiser Lifecycle Data
        $raiserLifecycles = [];
        foreach ($raisers as $raiser) {
            $pigTypeName = $raiser->pigType ? $raiser->pigType->name : 'Fattening';
            $raiserLifecycles[$raiser->id] = [
                'name' => $raiser->name,
                'status' => $raiser->status,
                'categories' => $this->getLifecycleCategories($pigTypeName),
            ];
        }

        return view('pages.dashboard', [
            'pageTitle' => 'Dashboard',
            'raisers' => $raisers,
            'raiserLifecycles' => $raiserLifecycles,
            'investmentSummary' => $investmentSummary,
            'user' => [
                'name' => 'Admin',
                'role' => 'System Administrator',
                'initials' => 'DL',
            ],
        ]);
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
            'Piglet' => [
                [
                    'label' => 'Vitamins',
                    'duration' => '3 days after born',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Booster',
                    'duration' => '10 days booster',
                    'status' => 'completed',
                ],
                [
                    'label' => 'Vitamins & capon',
                    'duration' => '14 days for vitamins',
                    'status' => 'in-progress',
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

    private function formatCompactNumber(int $amount): string
    {
        return number_format($amount);
    }

    public function downloadReport(Raiser $raiser)
    {
        $fileName = 'raiser-report-' . $raiser->code . '-' . now()->format('Y-m-d') . '.csv';

        $callback = function() use ($raiser) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['RAISER LIFECYCLE REPORT']);
            fputcsv($file, []);
            fputcsv($file, ['Raiser Name:', $raiser->name]);
            fputcsv($file, ['Raiser ID:', $raiser->code]);
            fputcsv($file, ['Location:', $raiser->location]);
            fputcsv($file, ['Status:', $raiser->status]);
            fputcsv($file, ['Report Date:', now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            // Lifecycle data
            foreach ($lifecycles as $categoryName => $stages) {
                fputcsv($file, [$categoryName]);
                fputcsv($file, ['Stage', 'Duration', 'Status']);

                foreach ($stages as $stage) {
                    fputcsv($file, [
                        $stage['label'],
                        $stage['duration'],
                        ucfirst(str_replace('-', ' ', $stage['status']))
                    ]);
                }

                fputcsv($file, []);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
