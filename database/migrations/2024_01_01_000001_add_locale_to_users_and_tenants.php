<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('locale', 5)->default('en')->after('email');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('locale', 5)->default('en')->after('name');
            $table->string('currency', 3)->default('PKR')->after('locale');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('locale');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['locale', 'currency']);
        });
    }
};
