@php
    $user = auth()->user();
    $levelNames = [1=>'Pemula', 2=>'Penjelajah', 3=>'Pejuang', 4=>'Ahli', 5=>'Pakar', 6=>'Java Master'];
    $levelName  = $levelNames[$user->level] ?? 'Pemula';

    $xpForNextLevel = match($user->level) {
        1 => 80, 2 => 180, 3 => 350, 4 => 600, 5 => 1000, default => 1000
    };
    $xpCurrentMin = match($user->level) {
        1 => 0, 2 => 80, 3 => 180, 4 => 350, 5 => 600, default => 1000
    };
    $xpPct = $user->level >= 6 ? 100 : min(100, round((($user->xp - $xpCurrentMin) / max(1, $xpForNextLevel - $xpCurrentMin)) * 100));

    $currentRoute = request()->route()?->getName() ?? '';
@endphp

<aside class="sidebar w-64 flex-shrink-0 flex flex-col h-screen sticky top-0 overflow-y-auto z-20">
    {{-- Logo --}}
    <div class="p-6 border-b border-indigo-900/30">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center level-badge">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                </svg>
            </div>
            <div>
                <p class="font-bold text-white text-sm">JavaPath</p>
                <p class="text-xs font-mono" style="color:#64748b;">adaptive learning</p>
            </div>
        </div>
    </div>

    {{-- User info --}}
    <div class="px-4 py-4 border-b border-indigo-900/30">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white level-badge flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-semibold text-white text-sm truncate">{{ $user->name }}</p>
                <p class="text-xs" style="color:#64748b;">{{ $user->username }}</p>
            </div>
        </div>
        {{-- XP Bar --}}
        <div>
            <div class="flex justify-between text-xs mb-1.5" style="color:#64748b;">
                <span class="font-medium" style="color:#818cf8;">Lv.{{ $user->level }} {{ $levelName }}</span>
                <span>{{ $user->xp }} XP</span>
            </div>
            <div class="xp-bar">
                <div class="xp-fill" style="width: {{ $xpPct }}%;"></div>
            </div>
            @if($user->level < 6)
            <p class="text-xs mt-1" style="color:#475569;">{{ $xpForNextLevel - $user->xp }} XP ke level berikutnya</p>
            @else
            <p class="text-xs mt-1" style="color:#fbbf24;">⭐ Java Master!</p>
            @endif
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 p-4 space-y-1">
        <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ $currentRoute === 'siswa.dashboard' ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ $currentRoute === 'siswa.dashboard' ? '' : 'text-gray-400' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <a href="{{ route('siswa.ujian.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'siswa.ujian') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ str_starts_with($currentRoute, 'siswa.ujian') ? '' : 'text-gray-400' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Ujian
        </a>
        <a href="{{ route('siswa.badges.index') }}" class="nav-link {{ str_starts_with($currentRoute, 'siswa.badges') ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 text-sm font-medium {{ str_starts_with($currentRoute, 'siswa.badges') ? '' : 'text-gray-400' }}">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Badges
        </a>
    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-indigo-900/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-left" style="color:#f87171;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
