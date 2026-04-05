<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retail_product_id')->constrained('retail_products')->cascadeOnDelete();
            $table->enum('movement_type', ['add', 'deduct', 'distribute'])->comment('add: stock in, deduct: sale, distribute: to raiser');
            $table->unsignedInteger('quantity');
            $table->foreignId('raiser_id')->nullable()->constrained('raisers')->nullOnDelete()->comment('For distribute movements');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['retail_product_id', 'created_at']);
            $table->index('movement_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
