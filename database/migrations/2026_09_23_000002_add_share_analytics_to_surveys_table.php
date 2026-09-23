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
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('share_token', 64)->nullable()->unique()->after('status');
            $table->boolean('public_analytics_enabled')->default(false)->after('share_token');
        });

        // Automatically populate unique share_token for existing surveys
        $surveys = \Illuminate\Support\Facades\DB::table('surveys')->whereNull('share_token')->get();
        foreach ($surveys as $survey) {
            \Illuminate\Support\Facades\DB::table('surveys')
                ->where('id', $survey->id)
                ->update(['share_token' => \Illuminate\Support\Str::random(32)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropUnique(['share_token']);
            $table->dropColumn(['share_token', 'public_analytics_enabled']);
        });
    }
};
