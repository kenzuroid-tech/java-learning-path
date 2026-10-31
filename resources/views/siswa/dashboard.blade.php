@extends('layouts.siswa')

@section('title', 'Dashboard – JavaPath')

@section('extra-styles')
    /* Stage cards */
    .stage-card { transition: all 0.25s ease; cursor: pointer; }
    .stage-card:hover:not(.locked) { transform: translateY(-3px); border-color: rgba(99,102,241,0.5) !important; }
    .stage-card.locked { opacity: 0.5; cursor: not-allowed; }
    .stage-card.completed { border-color: rgba(16,185,129,0.4) !important; }
    .stage-card.active { border-color: rgba(251,191,36,0.4) !important; }

    .stat-card { transition: all 0.2s; }
    .stat-card:hover { transform: translateY(-2px); }

    /* Snake Layout */
    .snake-container {
        display: flex;
        flex-direction: column;
        max-width: 900px;
        margin: 0 auto;
        position: relative;
        padding-top: 1rem;
    }
    
    .snake-row {
        display: flex;
        width: 100%;
        position: relative;
        padding: 2rem 0;
    }

    @media (min-width: 768px) {
        .snake-row {
            justify-content: space-around;
        }
        .snake-row:nth-child(even) {
            flex-direction: row-reverse;
        }
        
        /* Connectors inside the row */
        .snake-row::before {
            content: '';
            position: absolute;
            top: 4.5rem; /* center of the node */
            left: 25%;
            right: 25%;
            height: 3px;
            background: rgba(99,102,241,0.2);
            z-index: 0;
        }

        /* Curves between rows */
        .snake-curve {
            position: absolute;
            width: 50%;
            height: 100%; 
            border: 3px solid rgba(99,102,241,0.2);
            top: 4.5rem;
            z-index: 0;
        }

        .snake-row:nth-child(odd) .snake-curve {
            right: 25%;
            border-left: none;
            border-top-right-radius: 60px;
            border-bottom-right-radius: 60px;
        }

        .snake-row:nth-child(even) .snake-curve {
            left: 25%;
            border-right: none;
            border-top-left-radius: 60px;
            border-bottom-left-radius: 60px;
        }

        .snake-row:last-child .snake-curve {
            display: none;
        }
    }

    @media (max-width: 767px) {
        /* Mobile: straight vertical timeline */
        .snake-row {
            flex-direction: column;
            padding: 0;
        }
        .snake-container::before {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            left: 2.5rem;
            width: 3px;
            background: rgba(99,102,241,0.2);
        }
        .snake-row::before, .snake-curve {
            display: none;
        }
    }

    .snake-item {
        position: relative;
        z-index: 10;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
        .snake-item {
            width: 45%;
            margin-bottom: 0;
        }
    }

    @media (max-width: 767px) {
        .snake-item {
            flex-direction: row;
            align-items: flex-start;
            padding-left: 1rem;
        }
        .snake-content {
            margin-left: 1rem;
            width: calc(100% - 4rem);
        }
    }

    .snake-node {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: #1e1e2d;
        border: 4px solid rgba(99,102,241,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        z-index: 10;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    @media (min-width: 768px) {
        .snake-node {
            margin-bottom: 1.5rem;
        }
    }
    
    .snake-content {
        width: 100%;
    }

    /* Node States */
    .snake-item.completed .snake-node {
        border-color: rgba(16,185,129,0.8);
        background: rgba(16,185,129,0.1);
        box-shadow: 0 0 15px rgba(16,185,129,0.3);
    }
    .snake-item.active .snake-node {
        border-color: rgba(251,191,36,0.8);
        background: rgba(251,191,36,0.1);
        box-shadow: 0 0 15px rgba(251,191,36,0.3);
    }
    .snake-item.locked .snake-node {
        border-color: rgba(99,102,241,0.1);
        background: rgba(255,255,255,0.02);
        opacity: 0.5;
    }
@endsection

@section('content')
    @php
        // Fallback icons for stages based on their order number
        $stageIcons = ['☕','📦','➕','🔀','🔄','📋','🔧','🏗️','🔒','🧬','📐','💡','🎯','🚀','⚡'];
        $totalStages = $stages->count();
    @endphp

    {{-- Welcome header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Halo, {{ explode(' ', $user->name)[0] }}! 👋</h1>
        <p class="text-gray-400 text-sm mt-1">Lanjutkan perjalanan belajar Java-mu hari ini.</p>
    </div>

    {{-- ── STAT CARDS ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $levelNames = [1=>'Pemula', 2=>'Penjelajah', 3=>'Pejuang', 4=>'Ahli', 5=>'Pakar', 6=>'Java Master'];
            $levelName  = $levelNames[$user->level] ?? 'Pemula';
        @endphp

        <x-summary-card 
            title="Total XP" 
            value="{{ number_format($user->xp) }}" 
            subtitle="Level {{ $user->level }} · {{ $levelName }}"
            iconBg="rgba(99,102,241,0.2)"
            subtitleColor="#818cf8"
        >
            <x-slot name="icon">
                <svg class="w-4 h-4" style="color:#818cf8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </x-slot>
        </x-summary-card>

        <x-summary-card 
            title="Stage Selesai" 
            value="{{ $completedCount }}" 
            subtitle="dari {{ $totalStages }} stage"
            iconBg="rgba(16,185,129,0.15)"
            subtitleColor="#34d399"
        >
            <x-slot name="icon">
                <svg class="w-4 h-4" style="color:#34d399;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </x-slot>
        </x-summary-card>

        <x-summary-card 
            title="Nilai Placement" 
            value="{{ $placementResult ? number_format($placementResult->nilai, 0) : '–' }}" 
            subtitle="{{ $placementResult ? 'Mulai Stage ' . $placementResult->stage_awal : 'Belum test' }}"
            iconBg="rgba(251,191,36,0.15)"
            subtitleColor="#fbbf24"
        >
            <x-slot name="icon">
                <svg class="w-4 h-4" style="color:#fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </x-slot>
        </x-summary-card>

        <x-summary-card 
            title="Badges" 
            value="{{ $user->badges->count() }}" 
            subtitle="dari {{ $totalBadges }} badge"
            iconBg="rgba(244,114,182,0.15)"
            subtitleColor="#f472b6"
        >
            <x-slot name="icon">
                <svg class="w-4 h-4" style="color:#f472b6;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </x-slot>
        </x-summary-card>
    </div>

    {{-- ── STAGE MAP ────────────────────────────────────────────── --}}
    <div class="glass rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-white">Peta Belajar Java</h2>
                <p class="text-xs text-gray-500 mt-0.5">Selesaikan setiap stage untuk membuka yang berikutnya</p>
            </div>
            <span class="text-xs px-3 py-1 rounded-full font-medium" style="background:rgba(99,102,241,0.15); color:#818cf8;">
                {{ $completedCount }}/{{ $totalStages }} stage
            </span>
        </div>

        <div class="snake-container">
            @foreach($stages->chunk(2) as $row)
            <div class="snake-row">
                @foreach($row as $stage)
                @php
                    $num        = $stage->urutan;
                    $icon       = $stageIcons[$num - 1] ?? '📘';
                    $progress   = $allProgress[$stage->id] ?? null;
                    $isUnlockedByPlacement = $progress?->stage_unlocked ?? false;
                    $hasMaterials = $stage->materials_count > 0;
                    $unlocked   = $isUnlockedByPlacement && $hasMaterials;
                    $completed  = $progress ? $progress->isFullyCompleted() : false;
                    $statusClass = $completed ? 'completed' : ($unlocked ? 'active' : 'locked');
                    $borderColor = $completed ? 'rgba(16,185,129,0.35)' : ($unlocked ? 'rgba(251,191,36,0.3)' : 'rgba(99,102,241,0.1)');
                    $bgColor     = $completed ? 'rgba(16,185,129,0.06)' : ($unlocked ? 'rgba(251,191,36,0.05)' : 'rgba(255,255,255,0.02)');
                @endphp

                <div class="snake-item {{ $statusClass }}">
                    <div class="snake-node">
                        @if($completed)
                            <span style="color:#34d399; font-size:1rem;">✓</span>
                        @elseif($unlocked)
                            <span style="color:#fbbf24; font-size:1rem; font-weight:bold;">{{ $num }}</span>
                        @else
                            <span style="color:rgba(99,102,241,0.4); font-size:1rem;">🔒</span>
                        @endif
                    </div>
                    
                    <div class="snake-content">
                        @if($unlocked)
                        <a href="{{ route('siswa.stage.show', $stage) }}" class="stage-card {{ $statusClass }} p-4 rounded-xl text-center relative block"
                             style="background:{{ $bgColor }}; border:1px solid {{ $borderColor }}; text-decoration:none;">
                        @else
                        <div class="stage-card {{ $statusClass }} p-4 rounded-xl text-center relative"
                             style="background:{{ $bgColor }}; border:1px solid {{ $borderColor }};">
                        @endif

                            <div class="text-2xl mb-2">{{ $icon }}</div>
                            <p class="text-xs font-semibold mb-0.5" style="color:{{ $completed ? '#34d399' : ($unlocked ? '#fbbf24' : '#64748b') }};">
                                Stage {{ $num }}
                            </p>
                            <p class="text-xs text-gray-400 leading-tight">{{ $stage->nama }}</p>

                            @if($progress && $unlocked && !$completed)
                            <div class="mt-2 space-y-0.5">
                                <div class="flex items-center justify-center gap-1 text-xs" style="color:{{ $progress->materi_selesai ? '#34d399' : '#64748b' }};">
                                    {{ $progress->materi_selesai ? '✓' : '○' }} Materi
                                </div>
                                <div class="flex items-center justify-center gap-1 text-xs" style="color:{{ $progress->kuis_selesai ? '#34d399' : '#64748b' }};">
                                    {{ $progress->kuis_selesai ? '✓' : '○' }} Kuis
                                </div>
                                <div class="flex items-center justify-center gap-1 text-xs" style="color:{{ $progress->praktikum_selesai ? '#34d399' : '#64748b' }};">
                                    {{ $progress->praktikum_selesai ? '✓' : '○' }} Praktikum
                                </div>
                            </div>
                            @elseif(!$unlocked)
                            <div class="mt-3 flex items-center justify-center gap-1 text-[10px] uppercase font-bold tracking-wider text-indigo-400/50">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Coming Soon
                            </div>
                            @endif

                        @if($unlocked)
                        </a>
                        @else
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                
                {{-- Curve connecting to the next row --}}
                <div class="snake-curve"></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── QUICK INFO ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Placement test result --}}
        <div class="glass rounded-xl p-5">
            <h3 class="font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Hasil Placement Test
            </h3>
            @if($placementResult)
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Nilai</span>
                    <span class="font-bold text-white">{{ number_format($placementResult->nilai, 0) }}/100</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Starting Stage</span>
                    <span class="font-semibold text-indigo-300">Stage {{ $placementResult->stage_awal }}</span>
                </div>
                <div class="w-full rounded-full h-1.5 mt-2" style="background:rgba(99,102,241,0.15);">
                    <div class="h-1.5 rounded-full" style="width:{{ $placementResult->nilai }}%; background:linear-gradient(90deg,#6366f1,#8b5cf6);"></div>
                </div>
            </div>
            @else
            <p class="text-sm text-gray-400">Belum ada data placement test.</p>
            @endif
        </div>

        {{-- Legend --}}
        <div class="glass rounded-xl p-5">
            <h3 class="font-semibold text-white mb-3">Keterangan Stage</h3>
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-4 h-4 rounded flex-shrink-0" style="background:rgba(16,185,129,0.3); border:1px solid rgba(16,185,129,0.5);"></div>
                    <span class="text-gray-300">Stage selesai (materi + kuis + praktikum)</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-4 h-4 rounded flex-shrink-0" style="background:rgba(251,191,36,0.15); border:1px solid rgba(251,191,36,0.4);"></div>
                    <span class="text-gray-300">Stage sedang berlangsung</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <div class="w-4 h-4 rounded flex-shrink-0" style="background:rgba(255,255,255,0.02); border:1px solid rgba(99,102,241,0.1);"></div>
                    <span class="text-gray-400">Stage terkunci</span>
                </div>
            </div>
            <p class="text-xs text-gray-600 mt-3">Selesaikan materi, kuis, dan praktikum untuk membuka stage berikutnya.</p>
        </div>
    </div>
@endsection
