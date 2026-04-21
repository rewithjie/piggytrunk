<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('raisers')) {
            return;
        }

        Schema::table('raisers', function (Blueprint $table) {
            if (!Schema::hasColumn('raisers', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            if (!Schema::hasColumn('raisers', 'pig_type_id')) {
                $table->unsignedBigInteger('pig_type_id')->nullable();
            }

            if (!Schema::hasColumn('raisers', 'capacity')) {
                $table->integer('capacity')->default(0);
            }

            if (!Schema::hasColumn('raisers', 'total_capacity')) {
                $table->integer('total_capacity')->default(0);
            }

            if (!Schema::hasColumn('raisers', 'total_investment')) {
                $table->decimal('total_investment', 15, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('raisers')) {
            return;
        }

        Schema::table('raisers', function (Blueprint $table) {
            if (Schema::hasColumn('raisers', 'total_investment')) {
                $table->dropColumn('total_investment');
            }

            if (Schema::hasColumn('raisers', 'total_capacity')) {
                $table->dropColumn('total_capacity');
            }

            if (Schema::hasColumn('raisers', 'capacity')) {
                $table->dropColumn('capacity');
            }

            if (Schema::hasColumn('raisers', 'pig_type_id')) {
                $table->dropColumn('pig_type_id');
            }

            if (Schema::hasColumn('raisers', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};

