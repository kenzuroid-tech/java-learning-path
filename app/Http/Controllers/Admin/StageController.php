<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stage;
use App\Models\ExamGroup;
use Illuminate\Http\Request;

class StageController extends Controller
{
    public function index()
    {
        $stages = Stage::with('examGroup')->orderBy('urutan')->paginate(15);

        return view('admin.stages.index', compact('stages'));
    }

    public function create()
    {
        $examGroups = ExamGroup::all();

        return view('admin.stages.create', compact('examGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'urutan'        => 'required|integer|min:1',
            'exam_group_id' => 'nullable|exists:exam_groups,id',
            'deskripsi'     => 'nullable|string',
        ]);

        Stage::create($validated);

        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil ditambahkan.');
    }

    public function show(Stage $stage)
    {
        $stage->load(['materials', 'questions', 'praktikum']);

        return view('admin.stages.show', compact('stage'));
    }

    public function edit(Stage $stage)
    {
        $examGroups = ExamGroup::all();

        return view('admin.stages.edit', compact('stage', 'examGroups'));
    }

    public function update(Request $request, Stage $stage)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'urutan'        => 'required|integer|min:1',
            'exam_group_id' => 'nullable|exists:exam_groups,id',
            'deskripsi'     => 'nullable|string',
        ]);

        $stage->update($validated);

        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil diperbarui.');
    }

    public function destroy(Stage $stage)
    {
        $stage->delete();

        return redirect()->route('admin.stages.index')
            ->with('success', 'Stage berhasil dihapus.');
    }
}
