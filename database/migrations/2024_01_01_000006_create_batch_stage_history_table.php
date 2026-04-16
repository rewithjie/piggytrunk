<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('batch_stage_history')) {
            Schema::create('batch_stage_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->onDelete('cascade');
            $table->foreignId('lifecycle_stage_id')->constrained('batch_lifecycle_stages');
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->enum('status', ['pending', 'in-progress', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_stage_history');
    }
};
