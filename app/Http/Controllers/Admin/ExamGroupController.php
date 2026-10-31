<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamGroup;
use Illuminate\Http\Request;

class ExamGroupController extends Controller
{
    public function index()
    {
        $examGroups = ExamGroup::withCount(['stages', 'questions', 'examResults'])
            ->paginate(15);

        return view('admin.exam-groups.index', compact('examGroups'));
    }

    public function create()
    {
        return view('admin.exam-groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'stage_dari'     => 'required|integer|min:1',
            'stage_sampai'   => 'required|integer|min:1|gte:stage_dari',
            'total_soal'     => 'required|integer|min:1',
            'nilai_minimum'  => 'required|integer|min:0|max:100',
        ]);

        ExamGroup::create($validated);

        return redirect()->route('admin.exam-groups.index')
            ->with('success', 'Exam Group berhasil ditambahkan.');
    }

    public function show(ExamGroup $examGroup)
    {
        $examGroup->load(['stages', 'questions', 'examResults']);

        return view('admin.exam-groups.show', compact('examGroup'));
    }

    public function edit(ExamGroup $examGroup)
    {
        return view('admin.exam-groups.edit', compact('examGroup'));
    }

    public function update(Request $request, ExamGroup $examGroup)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:255',
            'stage_dari'     => 'required|integer|min:1',
            'stage_sampai'   => 'required|integer|min:1|gte:stage_dari',
            'total_soal'     => 'required|integer|min:1',
            'nilai_minimum'  => 'required|integer|min:0|max:100',
        ]);

        $examGroup->update($validated);

        return redirect()->route('admin.exam-groups.index')
            ->with('success', 'Exam Group berhasil diperbarui.');
    }

    public function destroy(ExamGroup $examGroup)
    {
        $examGroup->delete();

        return redirect()->route('admin.exam-groups.index')
            ->with('success', 'Exam Group berhasil dihapus.');
    }
}
