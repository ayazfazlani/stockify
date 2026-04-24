<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitation_tokens', function (Blueprint $table) {
            if (! Schema::hasColumn('invitation_tokens', 'tenant_id')) {
                $table->string('tenant_id')->nullable()->after('token');
                $table->index('tenant_id');
            }

            // Allow the same email to be invited by different tenants.
            $table->dropUnique('invitation_tokens_email_unique');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('invitation_tokens', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->unique('email');

            if (Schema::hasColumn('invitation_tokens', 'tenant_id')) {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            }
        });
    }
};
