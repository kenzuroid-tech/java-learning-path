<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialCompletion;
use App\Models\Stage;
use App\Models\StudentProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MateriController extends Controller
{
    /**
     * List all stages with their materials.
     */
    public function index(): View
    {
        $user = auth()->user();
        $stages = Stage::with('materials')->orderBy('urutan')->get();
        $allProgress = $user->progress()->get()->keyBy('stage_id');

        return view('siswa.materi.index', compact('stages', 'allProgress'));
    }

    /**
     * Show a single material's content.
     */
    public function show(Material $material): View|RedirectResponse
    {
        $user = auth()->user();
        $stage = $material->stage;
        $progress = $user->stageProgress($stage->id);

        // Check if stage is unlocked
        if (!$progress || !$progress->stage_unlocked) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Stage untuk materi ini masih terkunci.');
        }

        // Get all materials in this stage for navigation
        $stageMaterials = $stage->materials()->orderBy('urutan')->get();
        $currentIndex = $stageMaterials->search(fn ($m) => $m->id === $material->id);
        $prevMaterial = $currentIndex > 0 ? $stageMaterials[$currentIndex - 1] : null;
        $nextMaterial = $currentIndex < $stageMaterials->count() - 1 ? $stageMaterials[$currentIndex + 1] : null;

        // Which materials has this user completed?
        $completedMaterialIds = MaterialCompletion::where('user_id', $user->id)
            ->whereIn('material_id', $stageMaterials->pluck('id'))
            ->pluck('material_id')
            ->flip(); // use as a Set for fast lookup

        $isCompleted = isset($completedMaterialIds[$material->id]);

        return view('siswa.materi.show', compact(
            'material',
            'stage',
            'progress',
            'stageMaterials',
            'prevMaterial',
            'nextMaterial',
            'completedMaterialIds',
            'isCompleted',
        ));
    }

    /**
     * Mark a material as completed, then update stage progress if all done.
     */
    public function markDone(Material $material): RedirectResponse
    {
        $user = auth()->user();
        $stage = $material->stage;
        $progress = $user->stageProgress($stage->id);

        if (!$progress || !$progress->stage_unlocked) {
            return redirect()->route('siswa.dashboard');
        }

        // Record completion (ignore if duplicate)
        MaterialCompletion::firstOrCreate([
            'user_id'     => $user->id,
            'material_id' => $material->id,
        ], [
            'completed_at' => now(),
        ]);

        // Check if ALL materials in this stage are now complete
        $totalMaterials  = $stage->materials()->count();
        $doneMaterials   = MaterialCompletion::where('user_id', $user->id)
            ->whereIn('material_id', $stage->materials()->pluck('id'))
            ->count();

        if ($totalMaterials > 0 && $doneMaterials >= $totalMaterials) {
            StudentProgress::updateOrCreate(
                ['user_id' => $user->id, 'stage_id' => $stage->id],
                ['materi_selesai' => true]
            );
        }

        return redirect()->route('siswa.stage.show', $stage)
            ->with('status', 'Materi "' . $material->judul . '" telah selesai!');
    }
}
