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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->enum('status', ['aberto', 'andamento', 'resolvido', 'fechado'])->default('aberto');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'muito alta'])->default('media');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade'); // Quem abriu o ticket
            $table->foreignId('tecnico_id')->nullable()->constrained('users')->onDelete('set null'); // Técnico responsável
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('type_id')->constrained('types')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
