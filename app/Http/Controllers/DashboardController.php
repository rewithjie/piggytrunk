<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $raisers = Raiser::orderBy('name')->limit(10)->get();

        // Investment Summary Data
        $investmentSummary = [
            'totalActive' => 1850000,
            'batchCount' => 4,
            'allocation' => [
                'fattening' => 650000,
                'sow' => 770000,
                'boar' => 430000,
            ],
            'totalCapital' => 1850000,
            'expectedProfit' => 340000,
        ];

        // Raiser Lifecycle Data
        $raiserLifecycles = [];
        foreach ($raisers as $raiser) {
            $raiserLifecycles[$raiser->id] = [
                'name' => $raiser->name,
                'status' => $raiser->status,
                'categories' => $this->getLifecycleCategories($raiser->pig_type),
            ];
        }

        return view('pages.dashboard', [
            'pageTitle' => 'Dashboard',
            'raisers' => $raisers,
            'raiserLifecycles' => $raiserLifecycles,
            'investmentSummary' => $investmentSummary,
            'user' => [
                'name' => 'De Luna Admin',
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
                    'label' => 'Breeder',
                    'duration' => '100 days',
                    'status' => 'pending',
                ],
                [
                    'label' => 'Milk Maker',
                    'duration' => 'Nursing',
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
