<?php

namespace App\Http\Controllers;

use App\Models\Raiser;
use Illuminate\View\View;

class InvestmentController extends Controller
{
    public function index(): View
    {
        // Fetch actual investments from database with all relationships
        $investments = \App\Models\Investment::with(['batch.raiser', 'batch.pigType'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate actual summary from database
        $totalCapital = $investments->sum('total_amount');
        $activeInvestments = $investments->where('status', 'Active')->count();

        $summary = [
            ['label' => 'Total Capital', 'value' => '₱ ' . number_format($totalCapital, 0)],
            ['label' => 'Active Investments', 'value' => $activeInvestments],
            ['label' => 'Total Investments', 'value' => $investments->count()],
        ];

        return view('pages.investment.index', [
            'pageTitle' => 'Investment',
            'summary' => $summary,
            'investments' => $investments,
            'user' => $this->user(),
        ]);
    }

    public function create(): View
    {
        $raisers = \App\Models\Raiser::all();
        return view('pages.investment.create', [
            'raisers' => $raisers,
            'pageTitle' => 'Create New Investment',
            'user' => $this->user(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        \Log::warning('=== INVESTMENT STORE CALLED ===');
        \Log::warning('Request data: ' . json_encode($request->all()));
        
        // Only authenticated admins can create investments (middleware already verified this, but validate session)
        if (!$request->session()->get('is_admin', false)) {
            \Log::warning('User is not admin (session check failed)');
            return redirect()->route('admin.login.form')->with('error', 'Unauthorized access.');
        }

        \Log::info('Admin authenticated via session');

        // Parse the date from mm/dd/yyyy format to Y-m-d
        $dateString = $request->input('investment_date');
        \Log::info('Raw date string: ' . $dateString);
        
        $parsedDate = null;
        
        // Try to parse mm/dd/yyyy format
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateString, $matches)) {
            $parsedDate = $matches[3] . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            \Log::info('Parsed date from mm/dd/yyyy: ' . $parsedDate);
        } else {
            // Try Y-m-d format
            $parsedDate = $dateString;
            \Log::info('Using date as-is (Y-m-d): ' . $parsedDate);
        }

        try {
            $validated = $request->validate([
                'raiser_id' => 'required|exists:raisers,id',
                'initial_capital' => 'required|numeric|min:0',
                'hog_type' => 'required|string',
                'total_hog' => 'required|integer|min:1',
            ]);

            \Log::info('Validation passed: ' . json_encode($validated));

            // Add the parsed date
            $validated['investment_date'] = $parsedDate;

            // Get raiser to find pig_type_id
            $raiser = \App\Models\Raiser::findOrFail($validated['raiser_id']);
            \Log::info('Raiser found: ' . $raiser->name);
            
            // Generate batch code
            $batchCount = \App\Models\Batch::withTrashed()->count() + 1;
            $batchCode = 'BATCH-' . str_pad($batchCount, 4, '0', STR_PAD_LEFT);

            // Create batch first
            $batch = \App\Models\Batch::create([
                'code' => $batchCode,
                'raiser_id' => $validated['raiser_id'],
                'pig_type_id' => $raiser->pig_type_id,
                'initial_quantity' => $validated['total_hog'],
                'current_quantity' => $validated['total_hog'],
                'start_date' => $validated['investment_date'],
                'status' => 'Active',
                'total_investment' => $validated['initial_capital'],
            ]);

            \Log::info('Batch created: ' . $batch->id . ' - ' . $batchCode);

            // Create investment
            $investmentCode = 'INV-' . str_pad(\App\Models\Investment::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
            $investment = \App\Models\Investment::create([
                'code' => $investmentCode,
                'batch_id' => $batch->id,
                'total_amount' => $validated['initial_capital'],
                'current_value' => $validated['initial_capital'],
                'investment_date' => $validated['investment_date'],
                'status' => 'Active',
            ]);

            \Log::info('Investment created: ' . $investment->id . ' - ' . $investmentCode);
            
            return redirect()->route('investments.index')->with('success', 'Investment created successfully! (' . $investmentCode . ')');
        } catch (\Exception $e) {
            \Log::error('Investment creation error: ' . $e->getMessage());
            \Log::error('Stack: ' . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Error creating investment: ' . $e->getMessage());
        }
    }

    public function show(int $raiser): View
    {
        $raiserData = Raiser::findOrFail($raiser);
        
        // Get lifecycle stages based on pig type
        $pigTypeName = $raiserData->pigType ? $raiserData->pigType->name : 'Fattening';
        $lifecycleStages = $this->getLifecycleCategories($pigTypeName);
        $inProgressStage = collect($lifecycleStages)
            ->firstWhere('status', 'in-progress');
        $stageLabel = $inProgressStage['label'] ?? 'Grower';
        
        // Build investment details
        $investmentDetails = [
            'raiser_id' => $raiserData->id,
            'raiser_name' => $raiserData->name,
            'raiser_phone' => $raiserData->phone,
            'raiser_email' => $raiserData->email,
            'raiser_address' => $raiserData->address,
            'raiser_status' => $raiserData->status,
            'hog_type' => $pigTypeName,
            'total_capacity' => $raiserData->total_capacity,
            'batch_code' => 'BATCH-' . str_pad(($raiserData->id), 2, '0', STR_PAD_LEFT),
            'initial_capital' => '₱ ' . number_format(650000 + ($raiserData->id * 100000), 0),
            'total_hog' => 120 - ($raiserData->id * 10),
            'investment_date' => '05/13/2026',
            'expected_return_date' => '09/15/2026',
            'stage' => $stageLabel,
            'roi_percentage' => '18.6%',
            'expected_profit' => '₱ 120,000',
            'current_value' => '₱ 780,000',
        ];

        return view('pages.investment.show', [
            'hideTopbarTitle' => true,
            'investment' => $investmentDetails,
            'lifecycleStages' => $lifecycleStages,
            'pageTitle' => 'Investment Details',
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


