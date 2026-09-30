<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('user_limit');
            $table->unsignedSmallInteger('duration_count')->default(1);
            $table->string('duration_unit', 8)->default('month');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('subscription_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('trial_days')->default(7);
            $table->timestamps();
        });

        $now = now();
        DB::table('subscription_plans')->insert([
            ['name' => 'Starter', 'user_limit' => 5, 'duration_count' => 1, 'duration_unit' => 'month', 'price' => 0, 'discounted_price' => null, 'is_active' => true, 'is_default' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Professional', 'user_limit' => 25, 'duration_count' => 1, 'duration_unit' => 'month', 'price' => 0, 'discounted_price' => null, 'is_active' => true, 'is_default' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Enterprise', 'user_limit' => 100, 'duration_count' => 1, 'duration_unit' => 'month', 'price' => 0, 'discounted_price' => null, 'is_active' => true, 'is_default' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('subscription_settings')->insert([
            'trial_days' => 7,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        Schema::table('tenants', function (Blueprint $table) {
            $table->foreignId('subscription_plan_id')->nullable()->constrained()->nullOnDelete();
        });

        DB::table('tenants')->where('subscription_plan', 'starter')->update([
            'subscription_plan_id' => DB::table('subscription_plans')->where('name', 'Starter')->value('id'),
        ]);
        DB::table('tenants')->where('subscription_plan', 'professional')->update([
            'subscription_plan_id' => DB::table('subscription_plans')->where('name', 'Professional')->value('id'),
        ]);
        DB::table('tenants')->where('subscription_plan', 'enterprise')->update([
            'subscription_plan_id' => DB::table('subscription_plans')->where('name', 'Enterprise')->value('id'),
        ]);
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subscription_plan_id');
        });

        Schema::dropIfExists('subscription_settings');
        Schema::dropIfExists('subscription_plans');
    }
};