<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_groups', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('stage_dari');
            $table->integer('stage_sampai');
            $table->integer('total_soal');
            $table->integer('nilai_minimum')->default(80);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_groups');
    }
};
