<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();  // Use string ID for Stancl tenancy (UUID)
            $table->string('name');
            $table->string('slug')->unique();           // acme-corp → acme-corp.yourapp.com
            $table->foreignId('owner_id')->nullable()->constrained('users');  // Nullable for single DB tenancy
            $table->string('status')->default('active'); // active, suspended, etc.
            // your custom columns may go here

            $table->timestamps();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
