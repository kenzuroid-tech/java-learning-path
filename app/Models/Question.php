<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'stage_id',
        'exam_group_id',
        'tipe',
        'tipe_soal',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'jawaban_benar',
        'kode_potongan',
        'expected_output',
        'topik',
        'tingkat_kesulitan',
    ];

    protected function casts(): array
    {
        return [
            'kode_potongan' => 'array',
        ];
    }

    // ── Relationships ──

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function examGroup(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function examAnswers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    // ── Type helpers ──

    public function isPilihanGanda(): bool
    {
        return $this->tipe_soal === 'pilihan_ganda';
    }

    public function isUrutanKode(): bool
    {
        return $this->tipe_soal === 'urutan_kode';
    }

    public function isTulisKode(): bool
    {
        return $this->tipe_soal === 'tulis_kode';
    }

    public function getTipeSoalLabelAttribute(): string
    {
        return match ($this->tipe_soal) {
            'pilihan_ganda' => 'Pilihan Ganda',
            'urutan_kode'   => 'Urutan Kode',
            'tulis_kode'    => 'Tulis Kode',
            default         => 'Pilihan Ganda',
        };
    }

    /**
     * Check if a student's answer is correct based on tipe_soal.
     */
    public function checkAnswer(mixed $jawaban): bool
    {
        return match ($this->tipe_soal) {
            'pilihan_ganda' => strtolower((string) $jawaban) === strtolower((string) $this->jawaban_benar),
            'tulis_kode'    => trim((string) $jawaban) === trim((string) $this->expected_output),
            'urutan_kode'   => $this->checkUrutanKode($jawaban),
            default         => false,
        };
    }

    /**
     * All-or-nothing check for code ordering.
     * $jawaban should be an array of strings in the order submitted.
     */
    private function checkUrutanKode(mixed $jawaban): bool
    {
        if (! is_array($jawaban) || ! is_array($this->kode_potongan)) {
            return false;
        }
        return array_values($jawaban) === array_values($this->kode_potongan);
    }
}
