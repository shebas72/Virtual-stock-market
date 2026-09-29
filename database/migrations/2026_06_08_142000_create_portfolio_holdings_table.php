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
        Schema::create('portfolio_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->decimal('average_cost', 12, 2)->default(0);
            $table->decimal('current_value', 15, 2)->default(0);
            $table->decimal('unrealized_pnl', 15, 2)->default(0);
            $table->decimal('unrealized_pnl_percent', 8, 4)->default(0.0000);
            $table->timestamps();
            
            $table->unique(['portfolio_id', 'stock_id']);
            $table->index('portfolio_id');
            $table->index('stock_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_holdings');
    }
};