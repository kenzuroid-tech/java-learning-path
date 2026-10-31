<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stage extends Model
{
    protected $fillable = [
        'nama',
        'urutan',
        'exam_group_id',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function examGroup(): BelongsTo
    {
        return $this->belongsTo(ExamGroup::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('urutan');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function praktikum(): HasMany
    {
        return $this->hasMany(Praktikum::class);
    }

    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentProgress::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }
}
