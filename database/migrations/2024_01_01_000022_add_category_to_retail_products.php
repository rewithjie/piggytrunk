<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('retail_products', function (Blueprint $table) {
            if (!Schema::hasColumn('retail_products', 'category')) {
                $table->enum('category', ['Feeds', 'Vitamins', 'Medicines', 'Growth Additives'])
                    ->default('Feeds')
                    ->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('retail_products', function (Blueprint $table) {
            if (Schema::hasColumn('retail_products', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
