<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('types', function (Blueprint $table) {
            // 1) Se já existe unique em 'nome', removemos para criar unique composto
            // Em muitos projetos o nome do índice é 'types_nome_unique'
            // try { $table->dropUnique('types_nome_unique'); } catch (\Throwable $e) {}
            try { $table->dropUnique(['nome']); } catch (\Throwable $e) {}

            // 2) Adiciona o vínculo com categories
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete()
                ->after('nome');

            // 3) Unique composto: (category_id, nome)
            $table->unique(['category_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropUnique(['category_id', 'nome']);
            $table->dropConstrainedForeignId('category_id');

            // Se quiser voltar ao unique simples em nome:
            $table->unique('nome');
        });
    }
};
