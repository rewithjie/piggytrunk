<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('batch_lifecycle_stages')) {
            Schema::create('batch_lifecycle_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('sequence');
            $table->integer('duration_days')->default(0);
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_lifecycle_stages');
    }
};
