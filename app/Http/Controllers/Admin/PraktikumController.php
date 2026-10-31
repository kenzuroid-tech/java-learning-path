<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Praktikum;
use App\Models\Stage;
use Illuminate\Http\Request;

class PraktikumController extends Controller
{
    public function index(Request $request)
    {
        $praktikums = Praktikum::with('stage')
            ->when($request->stage_id, fn ($q, $id) => $q->where('stage_id', $id))
            ->latest()
            ->paginate(15);

        $stages = Stage::orderBy('urutan')->get();

        return view('admin.praktikum.index', compact('praktikums', 'stages'));
    }

    public function create()
    {
        $stages = Stage::orderBy('urutan')->get();

        return view('admin.praktikum.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id'        => 'required|exists:stages,id',
            'judul'           => 'required|string|max:255',
            'instruksi'       => 'required|string',
            'kode_awal'       => 'nullable|string',
            'expected_output' => 'nullable|string',
            'hint_1'          => 'nullable|string',
            'hint_2'          => 'nullable|string',
            'hint_3'          => 'nullable|string',
        ]);

        Praktikum::create($validated);

        return redirect()->route('admin.praktikum.index')
            ->with('success', 'Praktikum berhasil ditambahkan.');
    }

    public function show(Praktikum $praktikum)
    {
        $praktikum->load('stage');

        return view('admin.praktikum.show', compact('praktikum'));
    }

    public function edit(Praktikum $praktikum)
    {
        $stages = Stage::orderBy('urutan')->get();

        return view('admin.praktikum.edit', compact('praktikum', 'stages'));
    }

    public function update(Request $request, Praktikum $praktikum)
    {
        $validated = $request->validate([
            'stage_id'        => 'required|exists:stages,id',
            'judul'           => 'required|string|max:255',
            'instruksi'       => 'required|string',
            'kode_awal'       => 'nullable|string',
            'expected_output' => 'nullable|string',
            'hint_1'          => 'nullable|string',
            'hint_2'          => 'nullable|string',
            'hint_3'          => 'nullable|string',
        ]);

        $praktikum->update($validated);

        return redirect()->route('admin.praktikum.index')
            ->with('success', 'Praktikum berhasil diperbarui.');
    }

    public function destroy(Praktikum $praktikum)
    {
        $praktikum->delete();

        return redirect()->route('admin.praktikum.index')
            ->with('success', 'Praktikum berhasil dihapus.');
    }
}
