@extends('layouts.siswa')

@section('title', 'Materi Belajar – JavaPath')

@section('extra-styles')
    .stage-section { transition: all 0.2s; }
    .stage-section:hover { border-color: rgba(99,102,241,0.35) !important; }
    .materi-item { transition: all 0.2s; }
    .materi-item:hover { background: rgba(99,102,241,0.08) !important; transform: translateX(4px); }
@endsection

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">📚 Materi Belajar</h1>
        <p class="text-gray-400 text-sm mt-1">Pelajari materi dari setiap stage secara berurutan.</p>
    </div>

    {{-- Stage list with materials --}}
    <div class="space-y-4">
        @foreach($stages as $stage)
        @php
            $progress = $allProgress[$stage->id] ?? null;
            $unlocked = $progress?->stage_unlocked ?? false;
            $completed = $progress ? $progress->isFullyCompleted() : false;
        @endphp
        <div class="stage-section glass rounded-2xl p-5 {{ !$unlocked ? 'opacity-50' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold"
                         style="background: {{ $completed ? 'rgba(16,185,129,0.2)' : ($unlocked ? 'rgba(99,102,241,0.2)' : 'rgba(100,116,139,0.2)') }};
                                color: {{ $completed ? '#34d399' : ($unlocked ? '#818cf8' : '#64748b') }};">
                        {{ $stage->urutan }}
                    </div>
                    <div>
                        <h2 class="font-semibold text-white text-sm">
                            Stage {{ $stage->urutan }}: {{ $stage->nama }}
                        </h2>
                        <p class="text-xs text-gray-500">
                            {{ $stage->materials->count() }} materi
                            @if($completed)
                                · <span class="text-emerald-400">✓ Selesai</span>
                            @elseif($unlocked)
                                · <span class="text-amber-400">Sedang berlangsung</span>
                            @else
                                · <span class="text-gray-600">Terkunci</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($unlocked)
                <a href="{{ route('siswa.stage.show', $stage) }}" class="text-xs px-3 py-1.5 rounded-lg font-medium transition hover:opacity-80" style="background:rgba(99,102,241,0.2); color:#818cf8;">
                    Lihat Stage →
                </a>
                @else
                <span class="text-xs text-gray-600">🔒</span>
                @endif
            </div>

            @if($unlocked && $stage->materials->isNotEmpty())
            <div class="space-y-1 ml-13">
                @foreach($stage->materials as $material)
                <a href="{{ route('siswa.materi.show', $material) }}" class="materi-item flex items-center gap-3 px-3 py-2 rounded-lg" style="background:rgba(255,255,255,0.02);">
                    <div class="w-6 h-6 rounded flex items-center justify-center text-xs" style="background:rgba(99,102,241,0.1); color:#818cf8;">
                        {{ $material->urutan }}
                    </div>
                    <span class="text-sm text-gray-300">{{ $material->judul }}</span>
                    <svg class="w-3 h-3 text-gray-600 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endforeach
            </div>
            @elseif($unlocked && $stage->materials->isEmpty())
            <p class="text-xs text-gray-600 ml-13 italic">Belum ada materi.</p>
            @endif
        </div>
        @endforeach
    </div>
@endsection
