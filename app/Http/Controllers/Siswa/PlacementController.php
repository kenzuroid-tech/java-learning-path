<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PlacementResult;
use App\Models\Question;
use App\Models\Stage;
use App\Models\StudentProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlacementController extends Controller
{
    private const TOTAL_QUESTIONS = 15;

    /**
     * Show the placement test page.
     */
    public function show(): View
    {
        // Already done — redirect to dashboard
        if (auth()->user()->placement_done) {
            return view('siswa.placement.show', ['alreadyDone' => true]);
        }

        $questions = Question::where('tipe', 'placement')
            ->inRandomOrder()
            ->limit(self::TOTAL_QUESTIONS)
            ->get();

        // Shuffle kode_potongan for urutan_kode questions (don't reveal answer order)
        $questions = $questions->map(function ($q) {
            if ($q->isUrutanKode() && $q->kode_potongan) {
                $shuffled = $q->kode_potongan;
                shuffle($shuffled);
                $q->setAttribute('kode_potongan_shuffled', $shuffled);
            }
            return $q;
        });

        // Store question IDs in session for validation
        session(['placement_question_ids' => $questions->pluck('id')->toArray()]);

        return view('siswa.placement.show', compact('questions'));
    }

    /**
     * Handle placement test submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $questionIds = session('placement_question_ids', []);

        if (empty($questionIds)) {
            return redirect()->route('placement.show')
                ->with('error', 'Sesi habis, silakan mulai ulang placement test.');
        }

        $questions = Question::whereIn('id', $questionIds)->get()->keyBy('id');
        $answers   = $request->input('jawaban', []);
        $correct   = 0;
        $total     = $questions->count();

        foreach ($questions as $id => $question) {
            $jawaban = $answers[$id] ?? null;

            if ($question->isPilihanGanda()) {
                if ($jawaban !== null && strtolower($jawaban) === strtolower($question->jawaban_benar)) {
                    $correct++;
                }
            } elseif ($question->isTulisKode()) {
                if ($jawaban !== null && trim($jawaban) === trim($question->expected_output)) {
                    $correct++;
                }
            } elseif ($question->isUrutanKode()) {
                // jawaban comes as comma-separated string of indices
                $submitted = is_array($jawaban) ? $jawaban : explode(',', (string) $jawaban);
                $submitted = array_values(array_map('trim', $submitted));
                if ($submitted === array_values($question->kode_potongan ?? [])) {
                    $correct++;
                }
            }
        }

        $nilai      = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        $stageAwal  = $this->determineStartingStage($nilai);

        // Save placement result
        PlacementResult::create([
            'user_id'    => $user->id,
            'nilai'      => $nilai,
            'stage_awal' => $stageAwal,
        ]);

        // Unlock stages up to and including starting stage
        $stages = Stage::where('urutan', '<=', $stageAwal)->get();
        foreach ($stages as $stage) {
            StudentProgress::firstOrCreate(
                ['user_id' => $user->id, 'stage_id' => $stage->id],
                [
                    'materi_selesai'    => false,
                    'kuis_selesai'      => false,
                    'kuis_nilai'        => 0,
                    'praktikum_selesai' => false,
                    'stage_unlocked'    => true,
                ]
            );
        }

        // Create locked progress for remaining stages
        $remainingStages = Stage::where('urutan', '>', $stageAwal)->get();
        foreach ($remainingStages as $stage) {
            StudentProgress::firstOrCreate(
                ['user_id' => $user->id, 'stage_id' => $stage->id],
                [
                    'materi_selesai'    => false,
                    'kuis_selesai'      => false,
                    'kuis_nilai'        => 0,
                    'praktikum_selesai' => false,
                    'stage_unlocked'    => false,
                ]
            );
        }

        // Mark placement done
        $user->placement_done = true;
        $user->save();

        // Clear session
        session()->forget('placement_question_ids');

        return redirect()->route('placement.result')->with([
            'placement_nilai'      => $nilai,
            'placement_correct'    => $correct,
            'placement_total'      => $total,
            'placement_stage_awal' => $stageAwal,
        ]);
    }

    /**
     * Show placement result page.
     */
    public function result(): View
    {
        $user   = auth()->user();
        $result = $user->placementResult;

        return view('siswa.placement.result', compact('user', 'result'));
    }

    /**
     * Determine starting stage based on placement score.
     * 0–40   → Stage 1
     * 41–70  → Stage 3
     * 71–100 → Stage 6
     */
    private function determineStartingStage(float $nilai): int
    {
        return match (true) {
            $nilai >= 71 => 6,
            $nilai >= 41 => 3,
            default      => 1,
        };
    }
}
