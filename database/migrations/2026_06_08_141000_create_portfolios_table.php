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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('cash_balance', 15, 2)->default(100000.00);
            $table->decimal('initial_balance', 15, 2)->default(100000.00);
            $table->decimal('total_value', 15, 2)->default(100000.00);
            $table->decimal('portfolio_return', 10, 2)->default(0.00);
            $table->decimal('portfolio_return_percent', 8, 4)->default(0.0000);
            $table->integer('total_trades')->default(0);
            $table->integer('winning_trades')->default(0);
            $table->integer('losing_trades')->default(0);
            $table->timestamps();
            
            $table->unique(['user_id']);
            $table->index('total_value');
            $table->index('portfolio_return_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};