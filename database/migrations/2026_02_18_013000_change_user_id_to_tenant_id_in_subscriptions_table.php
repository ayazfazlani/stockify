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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Drop the old user_id column and index
            $table->dropIndex(['user_id', 'stripe_status']);
            $table->dropColumn('user_id');

            // Add the new tenant_id column (string because Stancl Tenancy uses string IDs)
            $table->string('tenant_id')->after('id')->index();

            $table->dropColumn('stripe_subscription_id');

            // Re-add index for performance
            $table->index(['tenant_id', 'stripe_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'stripe_status']);
            $table->dropColumn('tenant_id');

            $table->foreignId('user_id')->after('id');
            $table->index(['user_id', 'stripe_status']);
        });
    }
};
