<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_analyses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('nama_file_asli');
            $table->string('file_path');
            $table->string('file_type', 20); // pdf, pptx, docx, xlsx, csv, txt
            $table->string('file_size', 30)->nullable();
            $table->integer('total_baris_halaman')->default(0);
            $table->json('headers')->nullable();
            $table->json('preview_data')->nullable();
            $table->longText('extracted_text')->nullable();
            $table->json('ai_analysis')->nullable();
            $table->timestamp('ai_analyzed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_analyses');
    }
};
