<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('raisers')) {
            Schema::create('raisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('pig_type_id')->constrained('pig_types')->onDelete('restrict');
            $table->integer('capacity')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('remarks')->nullable();
            $table->decimal('total_investment', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_id');
            $table->index('pig_type_id');
            $table->index('status');
            $table->index('deleted_at');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('raisers');
    }
};
