<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementResult extends Model
{
    protected $fillable = [
        'user_id',
        'nilai',
        'stage_awal',
    ];

    protected function casts(): array
    {
        return [
            'nilai'      => 'float',
            'stage_awal' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
