<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('investments')) {
            Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('current_value', 12, 2);
            $table->decimal('expected_profit', 12, 2)->default(0);
            $table->decimal('actual_profit', 12, 2)->default(0);
            $table->date('investment_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Cancelled'])->default('Active');
            $table->decimal('roi_percentage', 5, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
