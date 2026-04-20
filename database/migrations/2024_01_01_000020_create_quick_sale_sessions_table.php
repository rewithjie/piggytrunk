<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('quick_sale_sessions')) {
            Schema::create('quick_sale_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('session_key')->unique();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('net_amount', 15, 2)->default(0);
                $table->enum('status', ['draft', 'confirmed', 'completed', 'cancelled'])->default('draft');
                $table->string('payment_method')->nullable();
                $table->string('customer_name')->nullable();
                $table->foreignId('raiser_id')->nullable()->constrained('raisers')->onDelete('restrict');
                $table->text('remarks')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('restrict');
                $table->timestamp('confirmed_at')->nullable();
                $table->timestamps();

                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('quick_sale_sessions');
    }
};
