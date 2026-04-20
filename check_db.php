<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Database Check ===" . PHP_EOL;
echo PHP_EOL . "Pig Types:" . PHP_EOL;
foreach (\App\Models\PigType::all() as $p) {
  echo "  ID: $p->id, Name: $p->name" . PHP_EOL;
}

echo PHP_EOL . "Raisers: " . \App\Models\Raiser::count() . PHP_EOL;
echo "Batches: " . \App\Models\Batch::count() . PHP_EOL;
echo "Investments: " . \App\Models\Investment::count() . PHP_EOL;
echo "Investors: " . \App\Models\Investor::count() . PHP_EOL;

echo PHP_EOL . "Investment Total Amount: " . \App\Models\Investment::sum('total_amount') . PHP_EOL;
echo "Investment Expected Profit: " . \App\Models\Investment::sum('expected_profit') . PHP_EOL;
