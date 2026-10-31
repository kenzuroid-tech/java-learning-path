<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ExamGroup;
use Illuminate\View\View;

class UjianController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $examGroups = ExamGroup::orderBy('stage_dari')->get();

        // Load user's exam results
        $examResults = $user->examResults()
            ->with('examGroup')
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('exam_group_id');

        return view('siswa.ujian.index', compact('examGroups', 'examResults'));
    }
}
