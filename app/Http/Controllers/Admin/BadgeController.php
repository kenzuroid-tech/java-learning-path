<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::paginate(15);

        return view('admin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:255',
            'kondisi'   => 'nullable|string|max:255',
        ]);

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil ditambahkan.');
    }

    public function show(Badge $badge)
    {
        return view('admin.badges.show', compact('badge'));
    }

    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon'      => 'nullable|string|max:255',
            'kondisi'   => 'nullable|string|max:255',
        ]);

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil diperbarui.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'Badge berhasil dihapus.');
    }
}
