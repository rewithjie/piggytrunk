<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('batches')) {
            Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('raiser_id')->constrained('raisers')->onDelete('cascade');
            $table->foreignId('pig_type_id')->constrained('pig_types')->onDelete('cascade');
            $table->integer('initial_quantity');
            $table->integer('current_quantity');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('status')->default('Planning');
            $table->decimal('total_investment', 15, 2)->default(0);
            $table->decimal('expected_profit', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
