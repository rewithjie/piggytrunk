<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('retail_transactions')) {
            Schema::create('retail_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('retail_product_id')->nullable()->constrained('retail_products')->onDelete('restrict');
            $table->foreignId('raiser_id')->nullable()->constrained('raisers')->onDelete('restrict');
            $table->integer('quantity')->default(0);
            $table->enum('transaction_type', ['sale', 'return'])->default('sale');
            $table->string('channel')->nullable();
            $table->string('status')->default('completed');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'check', 'card', 'online'])->default('cash');
            $table->string('customer_name')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('transaction_type');
            $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_transactions');
    }
};
