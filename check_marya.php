<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$raisers = \App\Models\Raiser::where('name', 'marya')->get();
echo "Found " . count($raisers) . " raiser(s) named marya\n";
foreach ($raisers as $r) {
    echo "ID: {$r->id}, Pig Type ID: {$r->pig_type_id}, Pig Type: " . ($r->pigType ? $r->pigType->name : 'N/A') . "\n";
}

echo "\n=== All Raisers ===\n";
$allRaisers = \App\Models\Raiser::with('pigType')->get();
foreach ($allRaisers as $r) {
    echo "ID: {$r->id}, Name: {$r->name}, Pig Type: " . ($r->pigType ? $r->pigType->name : 'N/A') . "\n";
}
