<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'JavaPath')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #080b1a; color: #e2e8f0; }
        .font-mono { font-family: 'Fira Code', monospace; }
        .bg-grid {
            background-image: linear-gradient(rgba(99,102,241,0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass { background: rgba(15,17,35,0.85); backdrop-filter: blur(20px); border: 1px solid rgba(99,102,241,0.18); }
        .glass-light { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); }
        .sidebar { background: rgba(8,11,26,0.95); border-right: 1px solid rgba(99,102,241,0.15); }

        /* XP Bar */
        .xp-bar { background: rgba(99,102,241,0.15); border-radius: 9999px; height: 8px; overflow: hidden; }
        .xp-fill { background: linear-gradient(90deg, #6366f1, #8b5cf6, #a78bfa); border-radius: 9999px; height: 100%; transition: width 1s cubic-bezier(.4,0,.2,1); animation: shimmer 2s infinite; }
        @keyframes shimmer { 0%,100% { opacity:1; } 50% { opacity:0.8; } }

        /* Nav links */
        .nav-link { transition: all 0.2s; border-radius: 10px; }
        .nav-link:hover { background: rgba(99,102,241,0.1); color: #a5b4fc; }
        .nav-link.active { background: rgba(99,102,241,0.2); color: #818cf8; }

        /* Level badge */
        .level-badge { background: linear-gradient(135deg, #6366f1, #8b5cf6); }

        .floating-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(100px);
            pointer-events: none;
            z-index: 0;
        }

        @yield('extra-styles')
    </style>
    @yield('extra-head')
</head>
<body class="min-h-screen bg-grid" style="overflow-x: hidden;">
    {{-- Background orbs --}}
    <div class="floating-orb w-96 h-96 top-0 right-0" style="background: rgba(99,102,241,0.08);"></div>
    <div class="floating-orb w-80 h-80 bottom-0 left-0" style="background: rgba(139,92,246,0.06);"></div>

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

    <div class="flex min-h-screen relative z-10">

        <x-sidebar />

        {{-- ── MAIN CONTENT ──────────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <main class="flex-1 p-6 relative z-10" style="overflow-x: hidden;">
            {{-- Flash messages --}}
            @if(session('status'))
            <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2" style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); color:#6ee7b7;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('status') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2" style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#fca5a5;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    </div>
</body>
</html>
