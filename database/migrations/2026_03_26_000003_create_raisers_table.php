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
                'name' => 'Norma Deuda',
                'location' => 'San Carlos',
                'batch' => 'BATCH-01',
                'pig_type' => 'Fattening',
                'status' => 'Active',
                'contact_person' => 'Norma Deuda',
                'phone' => '0917-555-0101',
                'email' => 'norma.deuda@example.com',
                'address' => 'Malasiqui, San Carlos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Josephine De Vera',
                'location' => 'San Carlos',
                'batch' => 'BATCH-02',
                'pig_type' => 'Fattening',
                'status' => 'Active',
                'contact_person' => 'Josephine De Vera',
                'phone' => '0917-555-0102',
                'email' => 'josephine.devera@example.com',
                'address' => 'Malasiqui, San Carlos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bella Mamaril',
                'location' => 'San Carlos',
                'batch' => 'BATCH-03',
                'pig_type' => 'Fattening',
                'status' => 'Active',
                'contact_person' => 'Bella Mamaril',
                'phone' => '0917-555-0103',
                'email' => 'bella.mamaril@example.com',
                'address' => 'Malasiqui, San Carlos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maria Gloria Benitez',
                'location' => 'San Carlos',
                'batch' => 'BATCH-04',
                'pig_type' => 'Sow',
                'status' => 'Active',
                'contact_person' => 'Maria Gloria Benitez',
                'phone' => '0917-555-0104',
                'email' => 'mariagloria.benitez@example.com',
                'address' => 'Malasiqui, San Carlos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elisa De Vera',
                'location' => 'Malasiqui',
                'batch' => 'BATCH-05',
                'pig_type' => 'Sow',
                'status' => 'Active',
                'contact_person' => 'Elisa De Vera',
                'phone' => '0917-555-0105',
                'email' => 'elisa.devera@example.com',
                'address' => 'Malasiqui, San Carlos',
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
