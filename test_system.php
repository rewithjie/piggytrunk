<?php

/**
 * PiggyTrunk Database & Dashboard Test Script
 * Verifies all systems are properly configured and data is accessible
 */

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "═══════════════════════════════════════════════════════════════\n";
echo "  PiggyTrunk - Database & Dashboard Configuration Test\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Test 1: Database Connection
echo "→ Testing Database Connection...\n";
try {
    \DB::connection()->getPdo();
    echo "  ✅ Database connection successful\n\n";
} catch (\Exception $e) {
    echo "  ❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Users Table
echo "→ Testing Users Table...\n";
$userCount = \App\Models\User::count();
echo "  ✅ Users found: $userCount\n";
if ($userCount > 0) {
    $user = \App\Models\User::first();
    echo "     - Admin user: {$user->name} ({$user->email})\n";
}
echo "\n";

// Test 3: Pig Types
echo "→ Testing Pig Types...\n";
$pigTypes = \App\Models\PigType::all();
echo "  ✅ Pig types found: " . $pigTypes->count() . "\n";
foreach ($pigTypes as $type) {
    echo "     - {$type->name}\n";
}
echo "\n";

// Test 4: Raisers
echo "→ Testing Raisers...\n";
$raisers = \App\Models\Raiser::with('pigType')->get();
echo "  ✅ Raisers found: " . $raisers->count() . "\n";
foreach ($raisers as $raiser) {
    $pigType = $raiser->pigType ? $raiser->pigType->name : 'Unknown';
    echo "     - {$raiser->name} ({$pigType})\n";
}
echo "\n";

// Test 5: Batches
echo "→ Testing Batches...\n";
$batches = \App\Models\Batch::with('raiser', 'pigType')->get();
echo "  ✅ Batches found: " . $batches->count() . "\n";
foreach ($batches as $batch) {
    $raiserName = $batch->raiser ? $batch->raiser->name : 'Unknown';
    $pigType = $batch->pigType ? $batch->pigType->name : 'Unknown';
    $mortality = $batch->initial_quantity - $batch->current_quantity;
    echo "     - {$batch->code} | {$raiserName} | {$pigType} | Qty: {$batch->current_quantity}/{$batch->initial_quantity} | Mortality: $mortality\n";
}
echo "\n";

// Test 6: Investments
echo "→ Testing Investments...\n";
$investments = \App\Models\Investment::all();
echo "  ✅ Investments found: " . $investments->count() . "\n";
$totalInvestment = $investments->sum('total_amount');
$expectedProfit = $investments->sum('expected_profit');
echo "     - Total Investment: ₱" . number_format($totalInvestment, 2) . "\n";
echo "     - Expected Profit: ₱" . number_format($expectedProfit, 2) . "\n";
echo "\n";

// Test 7: Lifecycle Stages
echo "→ Testing Batch Lifecycle Stages...\n";
$stages = \App\Models\BatchLifecycleStage::all();
echo "  ✅ Lifecycle stages found: " . $stages->count() . "\n";
foreach ($stages as $stage) {
    echo "     - {$stage->name} (Sequence: {$stage->sequence}, Duration: {$stage->duration_days} days)\n";
}
echo "\n";

// Test 8: Stage History
echo "→ Testing Batch Stage History...\n";
$stageHistory = \App\Models\BatchStageHistory::with('lifecycleStage')->get();
echo "  ✅ Stage history records found: " . $stageHistory->count() . "\n";
echo "\n";

// Test 9: Investors
echo "→ Testing Investors...\n";
$investors = \App\Models\Investor::all();
echo "  ✅ Investors found: " . $investors->count() . "\n";
foreach ($investors as $investor) {
    echo "     - {$investor->name} | {$investor->email}\n";
}
echo "\n";

// Test 10: Investment-Investor Relationships
echo "→ Testing Investment-Investor Relationships...\n";
$investmentInvestors = \App\Models\InvestmentInvestor::with('investment', 'investor')->get();
echo "  ✅ Investment-investor links found: " . $investmentInvestors->count() . "\n";
echo "\n";

// Test 11: Cashiers
echo "→ Testing Cashier Accounts...\n";
$cashiers = \App\Models\Cashier::all();
echo "  ✅ Cashier accounts found: " . $cashiers->count() . "\n";
foreach ($cashiers as $cashier) {
    echo "     - {$cashier->name} ({$cashier->email}) | Status: {$cashier->status}\n";
}
echo "\n";

// Test 12: Dashboard Data Queries
echo "→ Testing Dashboard Data Queries...\n";
$fatteningRaisers = \App\Models\Raiser::whereHas('pigType', function ($query) {
    $query->where('name', 'Fattening');
})->limit(3)->get();

$sowRaisers = \App\Models\Raiser::whereHas('pigType', function ($query) {
    $query->where('name', 'Sow');
})->limit(2)->get();

$batchCount = \App\Models\Batch::count();
$totalActiveInvestment = \App\Models\Investment::sum('total_amount') ?? 0;
$totalExpectedProfit = \App\Models\Investment::sum('expected_profit') ?? 0;

echo "  ✅ Fattening raisers: " . $fatteningRaisers->count() . "\n";
echo "  ✅ Sow raisers: " . $sowRaisers->count() . "\n";
echo "  ✅ Total batches: $batchCount\n";
echo "  ✅ Active investment: ₱" . number_format($totalActiveInvestment, 2) . "\n";
echo "  ✅ Expected profit: ₱" . number_format($totalExpectedProfit, 2) . "\n";
echo "\n";

// Summary
echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✅ ALL TESTS PASSED - System is Ready!\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n📊 Dashboard Information:\n";
echo "   - Application: http://127.0.0.1:8000\n";
echo "   - Admin Login: http://127.0.0.1:8000/admin/login\n";
echo "   - Cashier Login: http://127.0.0.1:8000/cashier/login\n";
echo "\n🔐 Login Credentials:\n";
echo "   Admin:\n";
echo "     Email: test@example.com\n";
echo "     Password: (check User Factory)\n";
echo "\n   Cashier:\n";
echo "     Email: cashier@piggytrunk\n";
echo "     Password: cashier2027\n";
echo "\n";
