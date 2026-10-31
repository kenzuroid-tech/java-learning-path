<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Placement Test – JavaPath</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #080b1a; color: #e2e8f0; }
        .bg-grid {
            background-image: linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass { background: rgba(15,17,35,0.9); backdrop-filter: blur(20px); border: 1px solid rgba(99,102,241,0.2); }
        .glow { box-shadow: 0 0 60px rgba(99,102,241,0.2), 0 0 120px rgba(99,102,241,0.08); }

        .score-ring {
            width: 160px; height: 160px;
            border-radius: 50%;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            position: relative;
        }
        .score-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: conic-gradient(#6366f1 var(--score-deg, 0deg), rgba(99,102,241,0.15) 0deg);
            z-index: -1;
        }
        .score-ring::after {
            content: '';
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            background: #080b1a;
            z-index: -1;
        }

        .stage-badge {
            animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(99,102,241,0.4); }

        .fade-in { animation: fadeIn 0.8s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-grid flex items-center justify-center p-4">

    @php
        $nilai      = session('placement_nilai', $result?->nilai ?? 0);
        $correct    = session('placement_correct', 0);
        $total      = session('placement_total', 15);
        $stageAwal  = session('placement_stage_awal', $result?->stage_awal ?? 1);
        $scoreDeg   = round(($nilai / 100) * 360);

        $stageNames = [
            1 => 'Pengenalan Java',
            2 => 'Variabel & Tipe Data',
            3 => 'Operator',
            4 => 'Percabangan',
            5 => 'Perulangan',
            6 => 'Array',
            7 => 'Method',
            8 => 'OOP Dasar',
            9 => 'Constructor & Encapsulation',
            10 => 'Inheritance & Polymorphism',
        ];
        $stageName = $stageNames[$stageAwal] ?? "Stage {$stageAwal}";

        $levelEmoji = match(true) {
            $nilai >= 71 => '🚀',
            $nilai >= 41 => '📖',
            default      => '🌱',
        };
        $levelText = match(true) {
            $nilai >= 71 => 'Level Lanjutan',
            $nilai >= 41 => 'Level Menengah',
            default      => 'Level Dasar',
        };
        $levelColor = match(true) {
            $nilai >= 71 => '#818cf8',
            $nilai >= 41 => '#fbbf24',
            default      => '#34d399',
        };
    @endphp

    <div class="w-full max-w-lg fade-in">

        {{-- Card --}}
        <div class="glass rounded-2xl overflow-hidden glow">

            {{-- Header --}}
            <div class="px-8 pt-8 pb-6 text-center" style="background: linear-gradient(180deg, rgba(99,102,241,0.1) 0%, transparent 100%);">
                <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color:#6366f1;">Placement Test Selesai</p>
                <h1 class="text-2xl font-bold text-white mb-1">Hasil Tesmu</h1>
                <p class="text-gray-400 text-sm">Halo, <strong class="text-indigo-300">{{ auth()->user()->name }}</strong>!</p>
            </div>

            {{-- Score --}}
            <div class="flex justify-center py-6">
                <div class="score-ring" style="--score-deg: {{ $scoreDeg }}deg;">
                    <span class="text-4xl font-bold text-white">{{ number_format($nilai, 0) }}</span>
                    <span class="text-xs text-gray-500 font-medium mt-0.5">dari 100</span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3 px-6 mb-6">
                <div class="text-center p-3 rounded-xl" style="background:rgba(255,255,255,0.04); border:1px solid rgba(99,102,241,0.15);">
                    <p class="text-2xl font-bold text-white">{{ $correct }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Benar</p>
                </div>
                <div class="text-center p-3 rounded-xl" style="background:rgba(255,255,255,0.04); border:1px solid rgba(99,102,241,0.15);">
                    <p class="text-2xl font-bold" style="color:#f87171;">{{ $total - $correct }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Salah</p>
                </div>
                <div class="text-center p-3 rounded-xl" style="background:rgba(255,255,255,0.04); border:1px solid rgba(99,102,241,0.15);">
                    <p class="text-2xl font-bold" style="color:#fbbf24;">{{ $total }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Total Soal</p>
                </div>
            </div>

            {{-- Starting Stage --}}
            <div class="mx-6 mb-6 p-5 rounded-2xl text-center stage-badge" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.1)); border: 1px solid rgba(99,102,241,0.3);">
                <p class="text-xs uppercase tracking-widest mb-2" style="color:#6366f1;">Kamu akan mulai dari</p>
                <div class="text-3xl font-bold text-white mb-1">Stage {{ $stageAwal }}</div>
                <div class="text-base font-medium mb-2" style="color:#a5b4fc;">{{ $stageName }}</div>
                <span class="inline-flex items-center gap-1.5 text-sm px-3 py-1 rounded-full font-medium"
                      style="background:rgba(255,255,255,0.06); color:{{ $levelColor }};">
                    {{ $levelEmoji }} {{ $levelText }}
                </span>
            </div>

            {{-- Info --}}
            <div class="mx-6 mb-6 px-4 py-3 rounded-xl text-sm" style="background:rgba(16,185,129,0.08); border:1px solid rgba(16,185,129,0.2); color:#6ee7b7;">
                @if($stageAwal >= 6)
                    🎯 Hebat! Kamu memiliki pemahaman Java yang kuat. Langsung ke materi lanjutan!
                @elseif($stageAwal >= 3)
                    👍 Bagus! Kamu sudah punya dasar Java. Mari perkuat dengan materi menengah!
                @else
                    🌱 Mulai dari awal itu baik! Kita bangun fondasi Java yang kuat bersamamu.
                @endif
            </div>

            {{-- CTA --}}
            <div class="px-6 pb-8">
                <a href="{{ route('siswa.dashboard') }}" class="btn-primary flex items-center justify-center gap-2 w-full py-3.5 rounded-xl font-semibold text-white text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Mulai Belajar Sekarang!
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center mt-4 text-xs text-gray-600">JavaPath · Adaptive Java Learning</p>
    </div>

</body>
</html>
