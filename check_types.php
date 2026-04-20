<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Pig Types ===\n";
$types = \App\Models\PigType::all();
foreach ($types as $type) {
    echo "ID: {$type->id}, Name: {$type->name}\n";
}

echo "\n=== Dashboard Raiser (marya) ===\n";
$raiser = \App\Models\Raiser::where('name', 'marya')->first();
if ($raiser) {
    echo "Name: {$raiser->name}\n";
    echo "Pig Type ID: {$raiser->pig_type_id}\n";
    echo "Pig Type: " . ($raiser->pigType ? $raiser->pigType->name : 'N/A') . "\n";
}
