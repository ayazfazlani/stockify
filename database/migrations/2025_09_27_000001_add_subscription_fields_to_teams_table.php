<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('subscription_plan')->nullable();
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('is_on_trial')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable();
            $table->integer('member_limit')->default(5);
            $table->integer('storage_limit')->default(5120); // 5GB in MB
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_plan',
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
                'is_on_trial',
                'is_active',
                'features',
                'member_limit',
                'storage_limit',
            ]);
        });
    }
};
