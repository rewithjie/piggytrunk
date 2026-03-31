<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retail_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->timestamps();
        });

        DB::table('retail_products')->insert([
            [
                'name' => 'Premium Hog Feed',
                'category' => 'Feeds',
                'price' => 1250.00,
                'stock' => 42,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Piglet Starter Mix',
                'category' => 'Feeds',
                'price' => 930.00,
                'stock' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Growth Booster Vitamins',
                'category' => 'Growth Additives',
                'price' => 380.00,
                'stock' => 58,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deworming Medicine',
                'category' => 'Medicines',
                'price' => 540.00,
                'stock' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_products');
    }
};

