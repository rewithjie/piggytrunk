<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('investors')) {
            Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('type', ['Individual', 'Organization'])->default('Individual');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('total_invested', 15, 2)->default(0);
            $table->decimal('total_returned', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
