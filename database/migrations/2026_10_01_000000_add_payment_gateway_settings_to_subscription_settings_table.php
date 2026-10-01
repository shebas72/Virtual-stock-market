<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_settings', function (Blueprint $table) {
            $table->boolean('payments_enabled')->default(true);
            $table->boolean('stripe_enabled')->default(true);
            $table->boolean('paypal_enabled')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('subscription_settings', function (Blueprint $table) {
            $table->dropColumn([
                'payments_enabled',
                'stripe_enabled',
                'paypal_enabled',
            ]);
        });
    }
};
