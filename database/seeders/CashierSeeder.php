<?php

namespace Database\Seeders;

use App\Models\Cashier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CashierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cashier::create([
            'name' => 'Cashier',
            'email' => 'cashier@piggytrunk',
            'password' => Hash::make('cashier2027'),
            'status' => 'active',
            'phone' => '09123456789',
            'address' => 'PiggyTrunk Office',
        ]);
    }
}
