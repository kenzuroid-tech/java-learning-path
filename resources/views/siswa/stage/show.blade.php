@extends('layouts.siswa')

@section('title', 'Stage ' . $stage->urutan . ': ' . $stage->nama . ' – JavaPath')

@section('extra-styles')
    .content-card { transition: all 0.25s ease; }
    .content-card:not(.locked):hover { transform: translateY(-2px); border-color: rgba(99,102,241,0.4) !important; }
    .progress-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .progress-dot.done { background: #34d399; }
    .progress-dot.pending { background: rgba(100,116,139,0.4); }
    .back-btn { transition: all 0.2s; }
    .back-btn:hover { background: rgba(99,102,241,0.15); }
    .locked-section { opacity: 0.55; cursor: not-allowed; }
    .lock-banner { border: 1px dashed rgba(251,191,36,0.35); background: rgba(251,191,36,0.05); border-radius: 12px; }
    .materi-done-badge { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); border-radius: 8px; }
@endsection

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('siswa.dashboard') }}" class="hover:text-indigo-400 transition">Dashboard</a>
        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-300">Stage {{ $stage->urutan }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('siswa.dashboard') }}" class="back-btn w-8 h-8 rounded-lg flex items-center justify-center" style="border: 1px solid rgba(99,102,241,0.2);">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Stage {{ $stage->urutan }}: {{ $stage->nama }}</h1>
                @if($stage->deskripsi)
                <p class="text-sm text-gray-400 mt-1">{{ $stage->deskripsi }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Overview --}}
    <x-progress-card :progress="$progress" />

    {{-- Materials Section --}}
    <div class="glass rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="w-5 h-5" style="color:#818cf8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Materi Belajar
            </h2>
            <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(99,102,241,0.15); color:#818cf8;">
                {{ $completedMaterialIds->count() }} / {{ $materials->count() }} selesai
            </span>
        </div>

        @if($materials->isEmpty())
            <div class="text-center py-8">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-gray-400 text-sm">Belum ada materi untuk stage ini.</p>
                <p class="text-gray-500 text-xs mt-1">Materi akan ditambahkan oleh admin.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($materials as $index => $material)
                @php $done = isset($completedMaterialIds[$material->id]); @endphp
                <x-lesson-card 
                    :material="$material" 
                    :index="$index" 
                    :total="$materials->count()" 
                    :done="$done" 
                />
                @endforeach
            </div>
        @endif
    </div>

    {{-- Kuis & Praktikum Section --}}
    @php $materiDone = $progress->materi_selesai; @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Kuis --}}
        <x-quiz-card 
            :materiDone="$materiDone"
            :questionsCount="$questionsCount"
            :kuisSelesai="$progress->kuis_selesai"
            :kuisNilai="$progress->kuis_nilai"
        />

        {{-- Praktikum --}}
        <div class="glass rounded-2xl p-5 {{ !$materiDone ? 'locked-section' : '' }}">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" style="color:#34d399;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                Praktikum
                @if(!$materiDone)
                    <span class="ml-auto flex items-center gap-1 text-xs text-yellow-500 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Terkunci
                    </span>
                @endif
            </h2>
            @if(!$materiDone)
                <div class="lock-banner p-4 text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-sm text-yellow-400 font-medium">Selesaikan semua materi terlebih dahulu</p>
                    <p class="text-xs text-gray-500 mt-1">Baca dan tandai semua materi sebagai selesai untuk membuka praktikum</p>
                </div>
            @elseif($praktikumList->isNotEmpty())
                <div class="space-y-2">
                    @foreach($praktikumList as $praktikum)
                    <div class="p-3 rounded-xl" style="background:rgba(16,185,129,0.05); border:1px solid rgba(16,185,129,0.15);">
                        <p class="text-sm font-medium text-gray-300">{{ $praktikum->judul }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($praktikum->instruksi, 80) }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <p class="text-gray-400 text-sm">Belum ada praktikum.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
