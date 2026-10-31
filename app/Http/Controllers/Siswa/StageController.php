<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MaterialCompletion;
use App\Models\Stage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StageController extends Controller
{
    public function show(Stage $stage): View|RedirectResponse
    {
        $user = auth()->user();
        $progress = $user->stageProgress($stage->id);

        // Check if stage is unlocked by placement test
        if (!$progress || !$progress->stage_unlocked) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Stage ini masih terkunci sesuai hasil placement test Anda.');
        }

        // Check if stage has materials (unlocked)
        if (!$stage->materials()->exists()) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Stage ini belum memiliki materi (Coming Soon).');
        }

        // Load materials, questions count, praktikum for this stage
        $materials = $stage->materials()->orderBy('urutan')->get();
        $questionsCount = $stage->questions()->where('tipe', 'kuis')->count();
        $praktikumList = $stage->praktikum()->get();

        // Which materials has the user completed?
        $completedMaterialIds = MaterialCompletion::where('user_id', $user->id)
            ->whereIn('material_id', $materials->pluck('id'))
            ->pluck('material_id')
            ->flip(); // use as a Set (key = material_id)

        return view('siswa.stage.show', compact(
            'stage',
            'progress',
            'materials',
            'questionsCount',
            'praktikumList',
            'completedMaterialIds',
        ));
    }
}
