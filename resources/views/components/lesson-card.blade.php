@props([
    'material',
    'index',
    'total',
    'done' => false
])

<a href="{{ route('siswa.materi.show', $material) }}"
   class="content-card block p-4 rounded-xl {{ $done ? 'materi-done-badge' : '' }}"
   style="background:{{ $done ? 'rgba(16,185,129,0.04)' : 'rgba(255,255,255,0.02)' }}; border:1px solid {{ $done ? 'rgba(16,185,129,0.3)' : 'rgba(99,102,241,0.12)' }};">
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold flex-shrink-0 transition-all"
             style="background:{{ $done ? 'rgba(16,185,129,0.2)' : 'rgba(99,102,241,0.15)' }}; color:{{ $done ? '#34d399' : '#818cf8' }};">
            @if($done)
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @else
                {{ $index + 1 }}
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-medium text-sm {{ $done ? 'text-emerald-300' : 'text-white' }}">{{ $material->judul }}</p>
            <p class="text-xs mt-0.5 {{ $done ? 'text-emerald-600' : 'text-gray-500' }}">
                {{ $done ? 'Sudah dibaca ✓' : 'Materi ' . ($index + 1) . ' dari ' . $total }}
            </p>
        </div>
        <svg class="w-4 h-4 flex-shrink-0 {{ $done ? 'text-emerald-500' : 'text-gray-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </div>
</a>
