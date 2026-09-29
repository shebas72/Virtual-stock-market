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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 10)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sector', 100)->nullable();
            $table->string('industry', 100)->nullable();
            $table->decimal('current_price', 12, 2)->default(0);
            $table->decimal('opening_price', 12, 2)->nullable();
            $table->decimal('previous_close', 12, 2)->nullable();
            $table->decimal('day_high', 12, 2)->nullable();
            $table->decimal('day_low', 12, 2)->nullable();
            $table->bigInteger('volume')->default(0);
            $table->decimal('market_cap', 15, 2)->nullable();
            $table->decimal('pe_ratio', 8, 2)->nullable();
            $table->decimal('dividend_yield', 5, 4)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('symbol');
            $table->index('sector');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};