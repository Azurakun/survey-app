<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->json('ai_analysis')->nullable()->after('tanggal_selesai');
            $table->timestamp('ai_analyzed_at')->nullable()->after('ai_analysis');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['ai_analysis', 'ai_analyzed_at']);
        });
    }
};
