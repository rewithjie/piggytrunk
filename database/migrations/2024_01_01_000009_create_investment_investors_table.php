<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('investment_investors')) {
            Schema::create('investment_investors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_id')->constrained('investments')->onDelete('cascade');
            $table->foreignId('investor_id')->constrained('investors')->onDelete('cascade');
            $table->decimal('amount_invested', 15, 2);
            $table->decimal('amount_returned', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['investment_id', 'investor_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_investors');
    }
};
