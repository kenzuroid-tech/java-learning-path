<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\Stage;
use App\Models\StudentProgress;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Load stages from database
        $stages = Stage::withCount('materials')->orderBy('urutan')->get();

        // Sync: create missing StudentProgress records for stages added after placement test
        $placementResult = $user->placementResult;
        if ($placementResult) {
            $existingStageIds = $user->progress()->pluck('stage_id')->toArray();
            $missingStages = $stages->filter(fn ($s) => !in_array($s->id, $existingStageIds));

            foreach ($missingStages as $stage) {
                StudentProgress::create([
                    'user_id'           => $user->id,
                    'stage_id'          => $stage->id,
                    'materi_selesai'    => false,
                    'kuis_selesai'      => false,
                    'kuis_nilai'        => 0,
                    'praktikum_selesai' => false,
                    'stage_unlocked'    => $stage->urutan <= $placementResult->stage_awal,
                ]);
            }
        }

        // Load user's progress for all stages, keyed by stage_id
        $allProgress = $user->progress()->with('stage')->get()->keyBy('stage_id');

        // Completed stages count
        $completedCount = $allProgress->filter(fn ($p) => $p->isFullyCompleted())->count();

        // Total badges
        $totalBadges = Badge::count();

        return view('siswa.dashboard', compact(
            'user',
            'stages',
            'allProgress',
            'completedCount',
            'placementResult',
            'totalBadges',
        ));
    }
}
