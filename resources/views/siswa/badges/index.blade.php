@extends('layouts.siswa')

@section('title', 'Badges – JavaPath')

@section('extra-styles')
    .badge-card { transition: all 0.3s ease; }
    .badge-card:hover:not(.locked) { transform: translateY(-4px) scale(1.02); }
    .badge-card.locked { opacity: 0.4; filter: grayscale(0.5); }
    .badge-card.earned { border-color: rgba(251,191,36,0.4) !important; }
    .badge-glow { box-shadow: 0 0 20px rgba(251,191,36,0.15); }
    @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
    .badge-icon-float { animation: float 3s ease-in-out infinite; }
@endsection

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">🏆 Koleksi Badges</h1>
        <p class="text-gray-400 text-sm mt-1">Kumpulkan badges dengan menyelesaikan tantangan.</p>
        <div class="flex items-center gap-3 mt-3">
            <span class="text-xs px-3 py-1 rounded-full" style="background:rgba(251,191,36,0.15); color:#fbbf24;">
                {{ count(array_filter($earnedBadgeIds)) }} / {{ $allBadges->count() }} badges
            </span>
        </div>
    </div>

    @if($allBadges->isEmpty())
        <div class="glass rounded-2xl p-12 text-center">
            <div class="text-4xl mb-3">🎖️</div>
            <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Badge</h3>
            <p class="text-gray-400 text-sm">Badge akan tersedia setelah admin menambahkannya.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($allBadges as $badge)
            @php
                $earned = in_array($badge->id, $earnedBadgeIds);
            @endphp
            <div class="badge-card {{ $earned ? 'earned badge-glow' : 'locked' }} glass rounded-2xl p-5 text-center relative">
                @if($earned)
                <div class="absolute -top-2 -right-2">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs" style="background:#d97706;">⭐</div>
                </div>
                @endif

                <div class="text-4xl mb-3 {{ $earned ? 'badge-icon-float' : '' }}">
                    {{ $badge->ikon ?? '🏅' }}
                </div>
                <h3 class="font-semibold text-sm {{ $earned ? 'text-amber-300' : 'text-gray-500' }}">
                    {{ $badge->nama }}
                </h3>
                <p class="text-xs mt-1 {{ $earned ? 'text-gray-400' : 'text-gray-600' }}">
                    {{ $badge->deskripsi ?? 'Selesaikan tantangan untuk mendapatkan badge ini.' }}
                </p>
                @if($earned)
                <p class="text-xs mt-2" style="color:#fbbf24;">✓ Diperoleh</p>
                @else
                <p class="text-xs mt-2 text-gray-600">🔒 Terkunci</p>
                @endif
            </div>
            @endforeach
        </div>
    @endif
@endsection
