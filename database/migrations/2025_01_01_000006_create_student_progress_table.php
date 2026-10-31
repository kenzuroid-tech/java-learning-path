<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('stages')->onDelete('cascade');
            $table->boolean('materi_selesai')->default(false);
            $table->boolean('kuis_selesai')->default(false);
            $table->decimal('kuis_nilai', 5, 2)->default(0);
            $table->boolean('praktikum_selesai')->default(false);
            $table->boolean('stage_unlocked')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'stage_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
