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
        // Add tracking_type to items
        Schema::table('items', function (Blueprint $table) {
            $table->string('tracking_type')->default('standard')->after('brand');
        });

        // Create Item Serials table
        Schema::create('item_serials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('serial_number');
            $table->string('status')->default('available'); // available, sold
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tenant_id');
            $table->timestamps();

            $table->unique(['serial_number', 'tenant_id']);
        });

        // Create Sales table
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 15, 2);
            $table->string('tenant_id');
            $table->timestamps();
        });

        // Add sale_id to transactions
        // Note: We use unsignedBigInteger and not foreignId because we might want to keep transactions even if a sale record is cleared (optional)
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_id')->nullable()->after('id');
            $table->index('sale_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('sale_id');
        });

        Schema::dropIfExists('sales');
        Schema::dropIfExists('item_serials');

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('tracking_type');
        });
    }
};
