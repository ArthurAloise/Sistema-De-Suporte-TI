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
        // O 'due_at' indica a data e hora limite para resolução do ticket
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('due_at')->nullable()->after('prioridade');
            $table->index('due_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['due_at']);
            $table->dropColumn('due_at');
        });
    }
};
