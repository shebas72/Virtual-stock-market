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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['buy', 'sell']);
            $table->integer('quantity');
            $table->decimal('price_per_share', 12, 2);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('commission', 10, 2)->default(0);
            $table->string('status', 20)->default('completed'); // pending, completed, cancelled, rejected
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('stock_id');
            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
