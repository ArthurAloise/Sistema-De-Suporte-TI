<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1) cria a coluna como nullable primeiro
            $table->unsignedBigInteger('setor_id')->nullable()->after('role_id');
        });

        // 2) preenche usuários antigos com o setor padrão (id=1)
        DB::table('users')->whereNull('setor_id')->update(['setor_id' => null]);

        // 3) cria a foreign key
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('setor_id')
                ->references('id')->on('setores')
                ->onUpdate('cascade')
                ->onDelete('set null'); // ou ->restrict() se preferir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['setor_id']);
            $table->dropColumn('setor_id');
        });
    }
};
