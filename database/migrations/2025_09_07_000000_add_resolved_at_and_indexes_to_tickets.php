<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('due_at');
            }
            $table->index(['status', 'prioridade']);
            $table->index(['category_id', 'type_id']);
            $table->index(['due_at', 'created_at']);
            $table->index(['usuario_id', 'tecnico_id']);
        });

        Schema::table('logs', function (Blueprint $table) {
            if (!Schema::hasColumn('logs', 'ip_address')) { /* ignore se não existir */
            }
            $table->index(['user_id', 'action']);
            $table->index(['route', 'method']);
            $table->index('created_at');
        });
    }
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'resolved_at')) $table->dropColumn('resolved_at');
            $table->dropIndex(['tickets_status_prioridade_index']);
            $table->dropIndex(['tickets_category_id_type_id_index']);
            $table->dropIndex(['tickets_due_at_created_at_index']);
            $table->dropIndex(['tickets_usuario_id_tecnico_id_index']);
        });
        Schema::table('logs', function (Blueprint $table) {
            $table->dropIndex(['logs_user_id_action_index']);
            $table->dropIndex(['logs_route_method_index']);
            $table->dropIndex(['logs_created_at_index']);
        });
    }
};
