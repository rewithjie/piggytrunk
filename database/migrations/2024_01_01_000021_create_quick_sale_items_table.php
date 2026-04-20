<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quick_sale_items')) {
            Schema::create('quick_sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quick_sale_session_id')->constrained('quick_sale_sessions')->onDelete('cascade');
                $table->foreignId('retail_product_id')->constrained('retail_products')->onDelete('restrict');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 15, 2);
                $table->decimal('total_price', 15, 2);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('net_price', 15, 2);
                $table->timestamps();

                $table->unique(['quick_sale_session_id', 'retail_product_id']);
                $table->index('quick_sale_session_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quick_sale_items');
    }
};
