<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'role',
    'xp',
    'level',
    'placement_done',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'placement_done' => 'boolean',
            'xp' => 'integer',
            'level' => 'integer',
        ];
    }

    // ── Relationships ──

    public function progress(): HasMany
    {
        return $this->hasMany(StudentProgress::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function placementResult(): HasOne
    {
        return $this->hasOne(PlacementResult::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'badge_user')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    // ── Helpers ──

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function addXp(int $amount): void
    {
        $this->xp += $amount;
        $this->level = self::calculateLevel($this->xp);
        $this->save();
    }

    public static function calculateLevel(int $xp): int
    {
        return match (true) {
            $xp >= 1000 => 6, // Java Master
            $xp >= 600  => 5, // Pakar
            $xp >= 350  => 4, // Ahli
            $xp >= 180  => 3, // Pejuang
            $xp >= 80   => 2, // Penjelajah
            default     => 1, // Pemula
        };
    }

    public static function levelName(int $level): string
    {
        return match ($level) {
            1 => 'Pemula',
            2 => 'Penjelajah',
            3 => 'Pejuang',
            4 => 'Ahli',
            5 => 'Pakar',
            6 => 'Java Master',
            default => 'Pemula',
        };
    }

    public function getLevelNameAttribute(): string
    {
        return self::levelName($this->level);
    }

    /**
     * Get progress for a specific stage.
     */
    public function stageProgress(int $stageId): ?StudentProgress
    {
        return $this->progress()->where('stage_id', $stageId)->first();
    }
}
