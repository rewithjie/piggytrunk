<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inventory_items')) {
            Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained('inventory_categories')->onDelete('restrict');
            $table->string('unit')->default('pcs');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('reorder_level', 10, 2)->default(0);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('code');
            $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
