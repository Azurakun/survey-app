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
        Schema::table('document_analyses', function (Blueprint $table) {
            $table->json('chat_history')->nullable()->after('ai_analyzed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_analyses', function (Blueprint $table) {
            $table->dropColumn('chat_history');
        });
    }
};
