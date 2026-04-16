<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Raiser;
use App\Models\PigType;
use Illuminate\Database\Seeder;

class RaiserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $fattening = PigType::where('name', 'Fattening')->first();
        $sow = PigType::where('name', 'Sow')->first();

        if ($user && $fattening && $sow) {
            Raiser::create([
                'user_id' => $user->id,
                'name' => 'John Doe',
                'code' => 'RAI-001',
                'phone' => '09171234567',
                'address' => 'San Carlos, Pangasinan',
                'pig_type_id' => $fattening->id,
                'capacity' => 100,
                'status' => 'active',
            ]);

            Raiser::create([
                'user_id' => $user->id,
                'name' => 'Maria Santos',
                'code' => 'RAI-002',
                'phone' => '09172234567',
                'address' => 'Malasiqui, Pangasinan',
                'pig_type_id' => $sow->id,
                'capacity' => 80,
                'status' => 'active',
            ]);
        }
    }
}
