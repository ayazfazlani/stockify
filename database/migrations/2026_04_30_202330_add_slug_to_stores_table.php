<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        DB::table('stores')->orderBy('id')->chunk(100, function ($stores) {
            foreach ($stores as $store) {
                if (empty($store->slug)) {
                    DB::table('stores')->where('id', $store->id)->update([
                        'slug' => Str::slug($store->name ?: 'store').'-'.$store->id,
                    ]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
