@extends('layouts.siswa')

@section('title', 'Ujian – JavaPath')

@section('extra-styles')
    .exam-card { transition: all 0.25s ease; }
    .exam-card:hover { transform: translateY(-2px); border-color: rgba(99,102,241,0.4) !important; }
@endsection

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">📝 Ujian</h1>
        <p class="text-gray-400 text-sm mt-1">Ujian menguji pemahaman Anda pada sekelompok stage.</p>
    </div>

    @if($examGroups->isEmpty())
        <div class="glass rounded-2xl p-12 text-center">
            <div class="text-4xl mb-3">📋</div>
            <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Ujian</h3>
            <p class="text-gray-400 text-sm">Ujian akan tersedia setelah admin menambahkannya.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($examGroups as $group)
            @php
                $results = $examResults[$group->id] ?? collect();
                $bestResult = $results->sortByDesc('nilai')->first();
                $attempts = $results->count();
            @endphp
            <div class="exam-card glass rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background:rgba(99,102,241,0.15);">
                            <svg class="w-6 h-6" style="color:#818cf8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ $group->nama }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Stage {{ $group->stage_dari }}–{{ $group->stage_sampai }} · {{ $group->total_soal }} soal · Min. nilai {{ $group->nilai_minimum }}
                            </p>
                        </div>
                    </div>
                    @if($bestResult)
                        <span class="text-xs px-3 py-1 rounded-full font-medium {{ $bestResult->isLulus() ? '' : '' }}"
                              style="background:{{ $bestResult->isLulus() ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }}; color:{{ $bestResult->isLulus() ? '#34d399' : '#f87171' }};">
                            {{ $bestResult->isLulus() ? '✓ Lulus' : '✗ Belum Lulus' }}
                        </span>
                    @endif
                </div>

                @if($results->isNotEmpty())
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(99,102,241,0.1);">
                    <p class="text-xs text-gray-500 mb-2">Riwayat ({{ $attempts }} percobaan)</p>
                    <div class="space-y-1.5">
                        @foreach($results->take(3) as $result)
                        <div class="flex items-center justify-between px-3 py-2 rounded-lg" style="background:rgba(255,255,255,0.02);">
                            <span class="text-xs text-gray-400">Percobaan {{ $result->attempt_ke }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-medium {{ $result->isLulus() ? 'text-emerald-400' : 'text-red-400' }}">
                                    {{ number_format($result->nilai, 0) }}/100
                                </span>
                                <span class="text-xs text-gray-600">{{ $result->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="mt-3 pt-3" style="border-top:1px solid rgba(99,102,241,0.1);">
                    <p class="text-xs text-gray-500 italic">Belum pernah mengerjakan ujian ini.</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    @endif
@endsection
