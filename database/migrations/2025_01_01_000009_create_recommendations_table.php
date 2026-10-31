<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_result_id')->constrained('exam_results')->onDelete('cascade');
            $table->string('topik');
            $table->enum('tipe_rekomendasi', ['materi', 'kuis', 'praktikum'])->default('materi');
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('cascade');
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
