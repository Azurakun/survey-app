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
        Schema::table('respondents', function (Blueprint $table) {
            $table->dropUnique(['survey_id', 'nisn']);
            $table->index(['survey_id', 'nisn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respondents', function (Blueprint $table) {
            $table->dropIndex(['survey_id', 'nisn']);
            $table->unique(['survey_id', 'nisn']);
        });
    }
};
