<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inventory_transactions')) {
            Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->onDelete('restrict');
            $table->enum('transaction_type', ['in', 'out', 'adjustment'])->default('in');
            $table->decimal('quantity', 10, 2);
            $table->text('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            $table->index('inventory_item_id');
            $table->index('transaction_type');
            $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
