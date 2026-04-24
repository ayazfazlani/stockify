<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'reorder_level')) {
                $table->unsignedInteger('reorder_level')->default(10)->after('quantity');
            }
            if (! Schema::hasColumn('items', 'reorder_quantity')) {
                $table->unsignedInteger('reorder_quantity')->default(20)->after('reorder_level');
            }
            if (! Schema::hasColumn('items', 'supplier_id')) {
                $table->foreignId('supplier_id')->nullable()->after('store_id')->constrained('suppliers')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'supplier_id')) {
                $table->dropConstrainedForeignId('supplier_id');
            }
            if (Schema::hasColumn('items', 'reorder_quantity')) {
                $table->dropColumn('reorder_quantity');
            }
            if (Schema::hasColumn('items', 'reorder_level')) {
                $table->dropColumn('reorder_level');
            }
        });
    }
};
