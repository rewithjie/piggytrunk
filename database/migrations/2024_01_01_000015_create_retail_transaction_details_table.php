<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('retail_transaction_details')) {
            Schema::create('retail_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retail_transaction_id')->constrained('retail_transactions')->onDelete('cascade');
            $table->foreignId('retail_product_id')->constrained('retail_products')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->index('retail_transaction_id');
            $table->index('retail_product_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_transaction_details');
    }
};
