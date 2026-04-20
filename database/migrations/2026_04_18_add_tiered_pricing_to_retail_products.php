<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('retail_products', function (Blueprint $table) {
            if (!Schema::hasColumn('retail_products', 'price_per_sack')) {
                $table->decimal('price_per_sack', 10, 2)->nullable()->after('selling_price');
                $table->decimal('price_per_kilo', 10, 2)->nullable()->after('price_per_sack');
                $table->decimal('price_per_half_kilo', 10, 2)->nullable()->after('price_per_kilo');
                $table->decimal('price_per_quarter_kilo', 10, 2)->nullable()->after('price_per_half_kilo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retail_products', function (Blueprint $table) {
            if (Schema::hasColumn('retail_products', 'price_per_sack')) {
                $table->dropColumn([
                    'price_per_sack',
                    'price_per_kilo',
                    'price_per_half_kilo',
                    'price_per_quarter_kilo',
                ]);
            }
        });
    }
};
