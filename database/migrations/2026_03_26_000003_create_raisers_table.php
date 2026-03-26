<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raisers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('location');
            $table->string('batch');
            $table->string('pig_type');
            $table->string('status');
            $table->string('contact_person');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('address');
            $table->timestamps();
        });

        DB::table('raisers')->insert([
            [
                'code' => 'RSR-0012',
                'name' => 'Dela Cruz Farms',
                'location' => 'Bulacan',
                'batch' => 'BATCH-01',
                'pig_type' => 'Male Pig',
                'status' => 'Active',
                'contact_person' => 'Maria Dela Cruz',
                'phone' => '0917-555-0101',
                'email' => 'delacruz@example.com',
                'address' => 'San Jose, Bulacan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RSR-0045',
                'name' => 'Santos Piggery',
                'location' => 'Pampanga',
                'batch' => 'BATCH-04',
                'pig_type' => 'Fattening',
                'status' => 'Active',
                'contact_person' => 'Joel Santos',
                'phone' => '0917-555-0102',
                'email' => 'santos@example.com',
                'address' => 'Angeles, Pampanga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'RSR-0082',
                'name' => 'Green Meadows',
                'location' => 'Tarlac',
                'batch' => 'No active batch',
                'pig_type' => 'Mother Pig',
                'status' => 'Inactive',
                'contact_person' => 'Leah Mendoza',
                'phone' => '0917-555-0103',
                'email' => 'greenmeadows@example.com',
                'address' => 'Capas, Tarlac',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('raisers');
    }
};
