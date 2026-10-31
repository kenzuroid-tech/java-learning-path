<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'user_id',
        'stage_id',
        'materi_selesai',
        'kuis_selesai',
        'kuis_nilai',
        'praktikum_selesai',
        'stage_unlocked',
    ];

    protected function casts(): array
    {
        return [
            'materi_selesai' => 'boolean',
            'kuis_selesai' => 'boolean',
            'kuis_nilai' => 'decimal:2',
            'praktikum_selesai' => 'boolean',
            'stage_unlocked' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * Check if this stage is fully completed (all 3 requirements met).
     */
    public function isFullyCompleted(): bool
    {
        return $this->materi_selesai && $this->kuis_selesai && $this->praktikum_selesai;
    }
}
