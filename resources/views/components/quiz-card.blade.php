@props([
    'materiDone',
    'questionsCount' => 0,
    'kuisSelesai' => false,
    'kuisNilai' => 0,
])

<div class="glass rounded-2xl p-5 {{ !$materiDone ? 'locked-section' : '' }}">
    <h2 class="text-lg font-semibold text-white flex items-center gap-2 mb-4">
        <svg class="w-5 h-5" style="color:#fbbf24;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Kuis
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
            <p class="text-xs text-gray-500 mt-1">Baca dan tandai semua materi sebagai selesai untuk membuka kuis</p>
        </div>
    @elseif($questionsCount > 0)
        <div class="p-4 rounded-xl" style="background:rgba(251,191,36,0.05); border:1px solid rgba(251,191,36,0.15);">
            <p class="text-sm text-gray-300">{{ $questionsCount }} soal tersedia</p>
            <p class="text-xs text-gray-500 mt-1">
                @if($kuisSelesai)
                    Nilai kuis: <span class="text-emerald-400 font-medium">{{ number_format($kuisNilai, 0) }}</span>
                @else
                    Klik untuk mengerjakan kuis
                @endif
            </p>
        </div>
    @else
        <div class="text-center py-6">
            <svg class="w-8 h-8 mx-auto mb-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-400 text-sm">Belum ada soal kuis.</p>
        </div>
    @endif
</div>
