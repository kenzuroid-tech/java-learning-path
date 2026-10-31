<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Praktikum extends Model
{
    protected $table = 'praktikum';

    protected $fillable = [
        'stage_id',
        'judul',
        'instruksi',
        'kode_awal',
        'expected_output',
        'hint_1',
        'hint_2',
        'hint_3',
    ];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
