<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRetailTransactionsForeignKeys extends Migration
{
    public function up(): void
    {
        // Drop the old constraint and recreate it with cascade
        Schema::table('retail_transactions', function (Blueprint $table) {
            // Drop old foreign key constraint
            $table->dropForeign(['retail_product_id']);
            
            // Recreate with cascade for soft deletes
            $table->foreign('retail_product_id')
                ->references('id')
                ->on('retail_products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('retail_transactions', function (Blueprint $table) {
            // Drop the cascade constraint
            $table->dropForeign(['retail_product_id']);
            
            // Restore the original restrict constraint
            $table->foreign('retail_product_id')
                ->references('id')
                ->on('retail_products')
                ->onDelete('restrict');
        });
    }
}
