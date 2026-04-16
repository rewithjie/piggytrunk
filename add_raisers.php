<?php

require 'vendor/autoload.php';
require 'bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$fattening = \App\Models\PigType::where('name', 'Fattening')->first();
$sow = \App\Models\PigType::where('name', 'Sow')->first();
$user = \App\Models\User::first();

if ($fattening && $sow && $user) {
    \App\Models\Raiser::create([
        'user_id' => $user->id,
        'name' => 'Rejie',
        'code' => 'RAI-001',
        'phone' => '09171234567',
        'address' => 'San Carlos, Pangasinan',
        'pig_type_id' => $fattening->id,
        'capacity' => 100,
        'status' => 'active',
    ]);

    \App\Models\Raiser::create([
        'user_id' => $user->id,
        'name' => 'Marya',
        'code' => 'RAI-002',
        'phone' => '09172234567',
        'address' => 'Malasiqui, Pangasinan',
        'pig_type_id' => $sow->id,
        'capacity' => 80,
        'status' => 'active',
    ]);
    
    echo "✅ Raisers added successfully!\n";
} else {
    echo "❌ Missing data (PigTypes or User)\n";
}
