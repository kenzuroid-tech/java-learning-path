<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: Can't ALTER COLUMN, need to recreate table
            $this->recreateTableForSQLite();
        } else {
            // PostgreSQL and others
            Schema::table('questions', function (Blueprint $table) {
                $table->string('tipe_soal')->default('pilihan_ganda')->after('tipe');
                $table->jsonb('kode_potongan')->nullable()->after('opsi_d');
                $table->text('expected_output')->nullable()->after('kode_potongan');
            });

            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_a DROP NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_b DROP NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_c DROP NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_d DROP NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN jawaban_benar DROP NOT NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // For SQLite, would need full table recreation again - skip for simplicity
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn(['tipe_soal', 'kode_potongan', 'expected_output']);
            });
        } else {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropColumn(['tipe_soal', 'kode_potongan', 'expected_output']);
            });
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_a SET NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_b SET NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_c SET NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN opsi_d SET NOT NULL');
            DB::statement('ALTER TABLE questions ALTER COLUMN jawaban_benar SET NOT NULL');
        }
    }

    private function recreateTableForSQLite(): void
    {
        // Create new table with updated schema
        Schema::create('questions_new', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->nullable()->constrained('stages')->onDelete('cascade');
            $table->foreignId('exam_group_id')->nullable()->constrained('exam_groups')->onDelete('cascade');
            $table->enum('tipe', ['kuis', 'ujian', 'placement']);
            $table->string('tipe_soal')->default('pilihan_ganda'); // NEW
            $table->text('pertanyaan');
            $table->text('opsi_a')->nullable(); // Made nullable
            $table->text('opsi_b')->nullable(); // Made nullable
            $table->text('opsi_c')->nullable(); // Made nullable
            $table->text('opsi_d')->nullable(); // Made nullable
            $table->json('kode_potongan')->nullable(); // NEW
            $table->text('expected_output')->nullable(); // NEW
            $table->enum('jawaban_benar', ['a', 'b', 'c', 'd'])->nullable(); // Made nullable
            $table->string('topik');
            $table->enum('tingkat_kesulitan', ['mudah', 'sedang', 'sulit'])->default('sedang');
            $table->timestamps();
        });

        // Copy data from old table
        DB::statement('INSERT INTO questions_new (id, stage_id, exam_group_id, tipe, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, topik, tingkat_kesulitan, created_at, updated_at) 
                       SELECT id, stage_id, exam_group_id, tipe, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban_benar, topik, tingkat_kesulitan, created_at, updated_at 
                       FROM questions');

        // Drop old table
        Schema::dropIfExists('questions');

        // Rename new table
        Schema::rename('questions_new', 'questions');
    }
};
