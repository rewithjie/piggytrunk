<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retail_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retail_product_id')->constrained('retail_products')->cascadeOnDelete();
            $table->foreignId('raiser_id')->nullable()->constrained('raisers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->unsignedInteger('quantity');
            $table->string('channel');
            $table->string('status');
            $table->decimal('total_amount', 10, 2);
            $table->date('transaction_date');
            $table->timestamps();
        });

        $premiumFeedId = DB::table('retail_products')->where('name', 'Premium Hog Feed')->value('id');
        $starterMixId = DB::table('retail_products')->where('name', 'Piglet Starter Mix')->value('id');
        $vitaminsId = DB::table('retail_products')->where('name', 'Growth Booster Vitamins')->value('id');
        $medicineId = DB::table('retail_products')->where('name', 'Deworming Medicine')->value('id');

        $delaCruzId = DB::table('raisers')->where('name', 'Dela Cruz Farms')->value('id');
        $santosId = DB::table('raisers')->where('name', 'Santos Piggery')->value('id');
        $greenMeadowsId = DB::table('raisers')->where('name', 'Green Meadows')->value('id');

        DB::table('retail_transactions')->insert([
            [
                'retail_product_id' => $premiumFeedId,
                'raiser_id' => $delaCruzId,
                'customer_name' => null,
                'quantity' => 3,
                'channel' => 'Walk-in',
                'status' => 'Completed',
                'total_amount' => 3750.00,
                'transaction_date' => now()->subDays(2)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'retail_product_id' => $starterMixId,
                'raiser_id' => $santosId,
                'customer_name' => null,
                'quantity' => 2,
                'channel' => 'Facebook Shop',
                'status' => 'Packed',
                'total_amount' => 1860.00,
                'transaction_date' => now()->subDays(2)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'retail_product_id' => $vitaminsId,
                'raiser_id' => null,
                'customer_name' => 'Ana Villanueva',
                'quantity' => 1,
                'channel' => 'Walk-in',
                'status' => 'Completed',
                'total_amount' => 380.00,
                'transaction_date' => now()->subDay()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'retail_product_id' => $medicineId,
                'raiser_id' => $greenMeadowsId,
                'customer_name' => null,
                'quantity' => 1,
                'channel' => 'Messenger',
                'status' => 'For Delivery',
                'total_amount' => 540.00,
                'transaction_date' => now()->subDay()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_transactions');
    }
};

