<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('subscription_items', function (Blueprint $table) {
            // Add meter_id column if using metered billing
            // $table->string('meter_id')->nullable()->after('stripe_price');

            // // Add other common Cashier columns if missing
            // $table->string('stripe_product')->nullable()->change();
            // // Note: The error shows it's trying to insert meter_event_name as well
            // $table->string('meter_event_name')->nullable()->after('meter_id');
        });
    }

    public function down()
    {
        Schema::table('subscription_items', function (Blueprint $table) {
            // $table->dropColumn(['meter_id', 'meter_event_name']);
        });
    }

    // uncomment when needed
};