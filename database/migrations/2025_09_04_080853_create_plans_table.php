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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. 'pro-monthly'
            $table->string('name');
            $table->string('stripe_price_id')->unique();
            $table->string('stripe_product_id')->nullable();
            $table->integer('amount'); // cents
            $table->string('currency', 3)->default('usd');
            $table->string('interval'); // monthly/yearly
            $table->text('features')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
    }
};
