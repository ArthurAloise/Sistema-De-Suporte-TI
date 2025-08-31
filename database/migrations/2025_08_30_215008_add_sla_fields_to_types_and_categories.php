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
        Schema::table('types', function (Blueprint $table) {
            $table->enum('default_priority', ['baixa','media','alta','muito alta'])->nullable()->after('nome');
            $table->unsignedSmallInteger('sla_hours')->nullable()->after('default_priority'); // alvo (h)
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->enum('default_priority', ['baixa','media','alta','muito alta'])->nullable()->after('nome');
            $table->unsignedSmallInteger('sla_hours')->nullable()->after('default_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types', function (Blueprint $table) {
            $table->dropColumn(['default_priority','sla_hours']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['default_priority','sla_hours']);
        });
    }
};
