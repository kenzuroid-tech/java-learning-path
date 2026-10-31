<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\View\View;

class BadgeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $allBadges = Badge::all();
        $earnedBadgeIds = $user->badges()->pluck('badges.id')->toArray();

        return view('siswa.badges.index', compact('allBadges', 'earnedBadgeIds'));
    }
}
