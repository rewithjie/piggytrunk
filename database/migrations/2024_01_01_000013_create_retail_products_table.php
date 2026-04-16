<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('retail_products')) {
            Schema::create('retail_products', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('cost_price', 15, 2);
                $table->decimal('selling_price', 15, 2);
                $table->string('unit')->default('pcs');
                $table->string('supplier')->nullable();
                $table->integer('quantity_in_stock')->default(0);
                $table->integer('reorder_level')->default(0);
                $table->string('image_path')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->text('remarks')->nullable();
                $table->softDeletes();
                $table->timestamps();

                $table->index('code');
                $table->index('status');
                $table->index('deleted_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_products');
    }
};
