<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('DRAFT'); // DRAFT, PUBLISHED, CLOSED
            $table->boolean('accepting_responses')->default(true);
            $table->timestamp('closed_at')->nullable();
            $table->text('custom_closed_message')->nullable();
            $table->boolean('limit_one_response')->default(true);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('tipe_pertanyaan');
            // SINGLE_CHOICE, MULTIPLE_CHOICE, LIKERT, SHORT_TEXT, LONG_TEXT, NUMBER, IMAGE_UPLOAD, DATE
            $table->text('teks_pertanyaan');
            $table->text('opsi_jawaban')->nullable(); // JSON string
            $table->boolean('wajib_diisi')->default(true);
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });

        Schema::create('respondents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('survey_id')->constrained('surveys')->onDelete('cascade');
            $table->string('nisn');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->unique(['survey_id', 'nisn']);
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('respondent_id')->constrained('respondents')->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained('questions')->onDelete('cascade');
            $table->longText('jawaban');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
        Schema::dropIfExists('respondents');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('surveys');
    }
};
