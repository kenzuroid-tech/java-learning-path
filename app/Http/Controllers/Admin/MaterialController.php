<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Stage;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $materials = Material::with('stage')
            ->when($request->stage_id, fn ($q, $id) => $q->where('stage_id', $id))
            ->orderBy('stage_id')
            ->orderBy('urutan')
            ->paginate(15);

        $stages = Stage::orderBy('urutan')->get();

        return view('admin.materials.index', compact('materials', 'stages'));
    }

    public function create()
    {
        $stages = Stage::orderBy('urutan')->get();

        return view('admin.materials.create', compact('stages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'judul'    => 'required|string|max:255',
            'konten'   => 'required|string',
            'urutan'   => 'required|integer|min:1',
        ]);

        Material::create($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil ditambahkan.');
    }

    public function show(Material $material)
    {
        $material->load('stage');

        return view('admin.materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $stages = Stage::orderBy('urutan')->get();

        return view('admin.materials.edit', compact('material', 'stages'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'stage_id' => 'required|exists:stages,id',
            'judul'    => 'required|string|max:255',
            'konten'   => 'required|string',
            'urutan'   => 'required|integer|min:1',
        ]);

        $material->update($validated);

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('admin.materials.index')
            ->with('success', 'Material berhasil dihapus.');
    }
}
