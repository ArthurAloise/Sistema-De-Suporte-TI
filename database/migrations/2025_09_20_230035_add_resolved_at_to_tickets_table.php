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
        Schema::table('tickets', function (Blueprint $table) {
            // após due_at, se existir; senão, só adicione
            $table->timestamp('resolved_at')->nullable()->after('due_at');
        });

        // (Opcional) Backfill: se já há tickets resolvidos, usar updated_at como resolução
//        DB::table('tickets')
//            ->where('status', 'resolvido')
//            ->whereNull('resolved_at')
//            ->update(['resolved_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('resolved_at');
        });
    }
};
