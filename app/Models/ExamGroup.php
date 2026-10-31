<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamGroup extends Model
{
    protected $fillable = [
        'nama',
        'stage_dari',
        'stage_sampai',
        'total_soal',
        'nilai_minimum',
    ];

    protected function casts(): array
    {
        return [
            'stage_dari' => 'integer',
            'stage_sampai' => 'integer',
            'total_soal' => 'integer',
            'nilai_minimum' => 'integer',
        ];
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}
