<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('subscription_plan')->default('starter');
            $table->decimal('subscription_price', 10, 2)->default(0);
            $table->string('subscription_status')->default('active');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'subscription_price',
                'subscription_status',
                'trial_ends_at',
                'subscription_ends_at',
            ]);
        });
    }
};