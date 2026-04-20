<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$raiser = \App\Models\Raiser::where('name', 'marya')->first();
if ($raiser) {
    echo "Raiser: {$raiser->name}\n";
    echo "Pig Type ID: {$raiser->pig_type_id}\n";
    echo "Pig Type Name: " . ($raiser->pigType ? $raiser->pigType->name : 'N/A') . "\n";
} else {
    echo "Raiser not found\n";
}
