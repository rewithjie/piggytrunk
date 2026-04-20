<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quick_sale_items', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['retail_product_id']);
            
            // Add new foreign key with cascade delete
            $table->foreign('retail_product_id')
                ->references('id')
                ->on('retail_products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('quick_sale_items', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['retail_product_id']);
            
            // Restore the old foreign key with restrict
            $table->foreign('retail_product_id')
                ->references('id')
                ->on('retail_products')
                ->onDelete('restrict');
        });
    }
};
