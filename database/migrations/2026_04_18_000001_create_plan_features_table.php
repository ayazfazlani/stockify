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
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('feature');           // enum value: 'analytics', 'max-items', etc.
            $table->string('value')->nullable(); // null for booleans, numeric string for quotas (-1 = unlimited)
            $table->timestamps();

            $table->unique(['plan_id', 'feature']); // Each feature assigned only once per plan
            $table->index('feature');                // Quick lookups by feature name
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
