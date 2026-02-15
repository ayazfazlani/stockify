<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('store_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('metric_name');
            $table->decimal('value', 10, 2);
            $table->json('metadata')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['store_id', 'metric_name', 'recorded_at']);
        });

        Schema::create('store_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->string('quota_name');
            $table->decimal('used', 10, 2);
            $table->decimal('limit', 10, 2);
            $table->timestamp('reset_at')->nullable();
            $table->timestamps();

            $table->unique(['store_id', 'quota_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_quotas');
        Schema::dropIfExists('store_metrics');
    }
};
