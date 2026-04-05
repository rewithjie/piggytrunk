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

        $lifecycles = [
            'SOW' => [
                ['label' => 'Pre-Starter', 'duration' => 'Up to 6 Days', 'status' => 'completed'],
                ['label' => 'Starter', 'duration' => '2 Weeks & 3 Weeks', 'status' => 'completed'],
                ['label' => 'Grower', 'duration' => '4 Weeks - 8 Weeks', 'status' => 'in-progress'],
                ['label' => 'Breeder', 'duration' => '100 Days', 'status' => 'pending'],
                ['label' => 'Milk Maker', 'duration' => 'Nursing', 'status' => 'pending'],
                ['label' => 'Separation', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
            'PIGLET' => [
                ['label' => 'Vitamins', 'duration' => '3 Days after born', 'status' => 'completed'],
                ['label' => 'Booster', 'duration' => '30 Days booster', 'status' => 'completed'],
                ['label' => 'Vitamins & cagun', 'duration' => '14 Days for vitamins', 'status' => 'in-progress'],
                ['label' => 'Separation', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
            'FATTENING' => [
                ['label' => 'Pre-Starter', 'duration' => 'Up to 6 Days', 'status' => 'completed'],
                ['label' => 'Starter', 'duration' => '2 Weeks & 2 Weeks', 'status' => 'completed'],
                ['label' => 'Grower', 'duration' => '3 Weeks & 2 Weeks', 'status' => 'in-progress'],
                ['label' => 'Selling', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
        ];

        return view('pages.dashboard', [
            'pageTitle' => 'Dashboard',
            'raisers' => $raisers,
            'lifecycles' => $lifecycles,
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

    public function downloadReport(Raiser $raiser)
    {
        $lifecycles = [
            'SOW' => [
                ['label' => 'Pre-Starter', 'duration' => 'Up to 6 Days', 'status' => 'completed'],
                ['label' => 'Starter', 'duration' => '2 Weeks & 3 Weeks', 'status' => 'completed'],
                ['label' => 'Grower', 'duration' => '4 Weeks - 8 Weeks', 'status' => 'in-progress'],
                ['label' => 'Breeder', 'duration' => '100 Days', 'status' => 'pending'],
                ['label' => 'Milk Maker', 'duration' => 'Nursing', 'status' => 'pending'],
                ['label' => 'Separation', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
            'PIGLET' => [
                ['label' => 'Vitamins', 'duration' => '3 Days after born', 'status' => 'completed'],
                ['label' => 'Booster', 'duration' => '30 Days booster', 'status' => 'completed'],
                ['label' => 'Vitamins & cagun', 'duration' => '14 Days for vitamins', 'status' => 'in-progress'],
                ['label' => 'Separation', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
            'FATTENING' => [
                ['label' => 'Pre-Starter', 'duration' => 'Up to 6 Days', 'status' => 'completed'],
                ['label' => 'Starter', 'duration' => '2 Weeks & 2 Weeks', 'status' => 'completed'],
                ['label' => 'Grower', 'duration' => '3 Weeks & 2 Weeks', 'status' => 'in-progress'],
                ['label' => 'Selling', 'duration' => 'Final Stage', 'status' => 'pending'],
            ],
        ];

        $fileName = 'raiser-report-' . $raiser->code . '-' . now()->format('Y-m-d') . '.csv';

        $callback = function() use ($raiser, $lifecycles) {
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
