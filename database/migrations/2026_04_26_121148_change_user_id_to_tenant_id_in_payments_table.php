<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('payments', 'tenant_id')) {
            Schema::table('payments', function (Blueprint $table) {
                // Add the new column as nullable first
                $table->string('tenant_id')->after('id')->nullable()->index();
            });

            // Migrate existing data based on user's current tenant or subscription tenant
            $payments = DB::table('payments')->get();
            foreach ($payments as $payment) {
                $tenantId = null;

                // Try via subscription first
                if (isset($payment->subscription_id) && $payment->subscription_id) {
                    $subscription = DB::table('subscriptions')->where('id', $payment->subscription_id)->first();
                    if ($subscription) {
                        $tenantId = $subscription->tenant_id;
                    }
                }

                // Fallback to user's current tenant if available
                if (!$tenantId && isset($payment->user_id) && $payment->user_id) {
                    $user = DB::table('users')->where('id', $payment->user_id)->first();
                    if ($user) {
                        $tenantId = $user->tenant_id;
                    }
                }

                if ($tenantId) {
                    DB::table('payments')->where('id', $payment->id)->update(['tenant_id' => $tenantId]);
                }
            }
        }

        Schema::table('payments', function (Blueprint $table) {
            // Now drop the old column and foreign key if it exists
            if (Schema::hasColumn('payments', 'user_id')) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {}
                
                try {
                    $table->dropIndex(['user_id', 'status']);
                } catch (\Exception $e) {}
                
                $table->dropColumn('user_id');
            }

            // Ensure index exists only if it doesn't already
            $this->createIndexIfNotExists('payments', ['tenant_id', 'status'], 'payments_tenant_id_status_index', $table);
        });
    }

    private function createIndexIfNotExists($table, $columns, $name, $blueprint)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$name}'");
        if (empty($indexes)) {
            $blueprint->index($columns, $name);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'tenant_id')) {
                try {
                    $table->dropIndex('payments_tenant_id_status_index');
                } catch (\Exception $e) {}
                $table->dropColumn('tenant_id');
            }

            if (!Schema::hasColumn('payments', 'user_id')) {
                $table->foreignId('user_id')->after('id')->nullable()->constrained()->onDelete('cascade');
                $table->index(['user_id', 'status']);
            }
        });
    }
};
