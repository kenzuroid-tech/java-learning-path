<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Stage;
use App\Models\ExamGroup;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::with(['stage', 'examGroup'])
            ->when($request->stage_id, fn ($q, $id) => $q->where('stage_id', $id))
            ->when($request->tipe, fn ($q, $tipe) => $q->where('tipe', $tipe))
            ->when($request->tipe_soal, fn ($q, $ts) => $q->where('tipe_soal', $ts))
            ->latest()
            ->paginate(15);

        $stages     = Stage::orderBy('urutan')->get();
        $examGroups = ExamGroup::all();

        return view('admin.questions.index', compact('questions', 'stages', 'examGroups'));
    }

    public function create()
    {
        $stages     = Stage::orderBy('urutan')->get();
        $examGroups = ExamGroup::all();

        return view('admin.questions.create', compact('stages', 'examGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id'         => 'nullable|exists:stages,id',
            'exam_group_id'    => 'nullable|exists:exam_groups,id',
            'tipe'             => 'required|in:quiz,exam,placement',
            'tipe_soal'        => 'required|in:pilihan_ganda,urutan_kode,tulis_kode',
            'pertanyaan'       => 'required|string',
            'opsi_a'           => 'nullable|string',
            'opsi_b'           => 'nullable|string',
            'opsi_c'           => 'nullable|string',
            'opsi_d'           => 'nullable|string',
            'jawaban_benar'    => 'nullable|string',
            'kode_potongan'    => 'nullable|array',
            'kode_potongan.*'  => 'string',
            'expected_output'  => 'nullable|string',
            'topik'            => 'nullable|string|max:255',
            'tingkat_kesulitan' => 'nullable|string|max:255',
        ]);

        Question::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil ditambahkan.');
    }

    public function show(Question $question)
    {
        $question->load(['stage', 'examGroup']);

        return view('admin.questions.show', compact('question'));
    }

    public function edit(Question $question)
    {
        $stages     = Stage::orderBy('urutan')->get();
        $examGroups = ExamGroup::all();

        return view('admin.questions.edit', compact('question', 'stages', 'examGroups'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'stage_id'         => 'nullable|exists:stages,id',
            'exam_group_id'    => 'nullable|exists:exam_groups,id',
            'tipe'             => 'required|in:quiz,exam,placement',
            'tipe_soal'        => 'required|in:pilihan_ganda,urutan_kode,tulis_kode',
            'pertanyaan'       => 'required|string',
            'opsi_a'           => 'nullable|string',
            'opsi_b'           => 'nullable|string',
            'opsi_c'           => 'nullable|string',
            'opsi_d'           => 'nullable|string',
            'jawaban_benar'    => 'nullable|string',
            'kode_potongan'    => 'nullable|array',
            'kode_potongan.*'  => 'string',
            'expected_output'  => 'nullable|string',
            'topik'            => 'nullable|string|max:255',
            'tingkat_kesulitan' => 'nullable|string|max:255',
        ]);

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Soal berhasil dihapus.');
    }
}
