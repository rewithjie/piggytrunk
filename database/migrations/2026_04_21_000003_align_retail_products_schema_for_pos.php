<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('retail_products')) {
            return;
        }

        Schema::table('retail_products', function (Blueprint $table) {
            if (!Schema::hasColumn('retail_products', 'code')) {
                $table->string('code')->nullable();
            }

            if (!Schema::hasColumn('retail_products', 'cost_price')) {
                $table->decimal('cost_price', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('retail_products', 'selling_price')) {
                $table->decimal('selling_price', 15, 2)->default(0);
            }

            if (!Schema::hasColumn('retail_products', 'unit')) {
                $table->string('unit')->default('pcs');
            }

            if (!Schema::hasColumn('retail_products', 'supplier')) {
                $table->string('supplier')->nullable();
            }

            if (!Schema::hasColumn('retail_products', 'quantity_in_stock')) {
                $table->integer('quantity_in_stock')->default(0);
            }

            if (!Schema::hasColumn('retail_products', 'reorder_level')) {
                $table->integer('reorder_level')->default(0);
            }

            if (!Schema::hasColumn('retail_products', 'image_path')) {
                $table->string('image_path')->nullable();
            }

            if (!Schema::hasColumn('retail_products', 'status')) {
                $table->string('status')->default('active');
            }

            if (!Schema::hasColumn('retail_products', 'remarks')) {
                $table->text('remarks')->nullable();
            }

            if (!Schema::hasColumn('retail_products', 'deleted_at')) {
                $table->softDeletes();
                $table->index('deleted_at');
            }
        });

        if (Schema::hasColumn('retail_products', 'price')) {
            DB::statement('UPDATE retail_products SET selling_price = COALESCE(selling_price, price, 0)');
            DB::statement('UPDATE retail_products SET cost_price = COALESCE(cost_price, price, 0)');
        }

        if (Schema::hasColumn('retail_products', 'stock')) {
            DB::statement('UPDATE retail_products SET quantity_in_stock = COALESCE(quantity_in_stock, stock, 0)');
        }

        if (Schema::hasColumn('retail_products', 'image')) {
            DB::statement('UPDATE retail_products SET image_path = COALESCE(image_path, image)');
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('retail_products')) {
            return;
        }

        Schema::table('retail_products', function (Blueprint $table) {
            if (Schema::hasColumn('retail_products', 'deleted_at')) {
                $table->dropIndex(['deleted_at']);
                $table->dropSoftDeletes();
            }

            if (Schema::hasColumn('retail_products', 'remarks')) {
                $table->dropColumn('remarks');
            }

            if (Schema::hasColumn('retail_products', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('retail_products', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('retail_products', 'reorder_level')) {
                $table->dropColumn('reorder_level');
            }

            if (Schema::hasColumn('retail_products', 'quantity_in_stock')) {
                $table->dropColumn('quantity_in_stock');
            }

            if (Schema::hasColumn('retail_products', 'supplier')) {
                $table->dropColumn('supplier');
            }

            if (Schema::hasColumn('retail_products', 'unit')) {
                $table->dropColumn('unit');
            }

            if (Schema::hasColumn('retail_products', 'selling_price')) {
                $table->dropColumn('selling_price');
            }

            if (Schema::hasColumn('retail_products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }

            if (Schema::hasColumn('retail_products', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};

