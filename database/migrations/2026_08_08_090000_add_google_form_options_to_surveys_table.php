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
            if (!Schema::hasColumn('surveys', 'accepting_responses')) {
                $table->boolean('accepting_responses')->default(true)->after('status');
            }
            if (!Schema::hasColumn('surveys', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('accepting_responses');
            }
            if (!Schema::hasColumn('surveys', 'custom_closed_message')) {
                $table->text('custom_closed_message')->nullable()->after('closed_at');
            }
            if (!Schema::hasColumn('surveys', 'limit_one_response')) {
                $table->boolean('limit_one_response')->default(true)->after('custom_closed_message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'accepting_responses',
                'closed_at',
                'custom_closed_message',
                'limit_one_response',
            ]);
        });
    }
};
