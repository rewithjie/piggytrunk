<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$users = \App\Models\User::count();
$raisers = \App\Models\Raiser::count();
$batches = \App\Models\Batch::count();
$investments = \App\Models\Investment::count();

echo "Database Status:\n";
echo "Users: $users\n";
echo "Raisers: $raisers\n";
echo "Batches: $batches\n";
echo "Investments: $investments\n";

if ($users > 0 && $raisers > 0 && $batches > 0 && $investments > 0) {
    echo "\n✅ Database is properly seeded!\n";
} else {
    echo "\n⚠️ Some data is missing!\n";
}
