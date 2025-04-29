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
        Schema::table('logs', function (Blueprint $table) {
            $table->string('action_name')->nullable()->after('controller');
            $table->text('description')->nullable()->after('record_id');
            $table->json('request_data')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn([
                'action_name',
                'description',
                'request_data'
            ]);
        });
    }
};
