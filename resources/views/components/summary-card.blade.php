@props([
    'title',
    'value',
    'subtitle' => null,
    'iconBg' => 'rgba(99,102,241,0.2)',
    'subtitleColor' => '#818cf8'
])

<div class="stat-card glass rounded-xl p-4">
    <div class="flex items-center gap-2 mb-2">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: {{ $iconBg }};">
            {{ $icon }}
        </div>
        <span class="text-xs text-gray-500 font-medium">{{ $title }}</span>
    </div>
    <p class="text-2xl font-bold text-white">{{ $value }}</p>
    @if($subtitle)
        <p class="text-xs mt-0.5" style="color: {{ $subtitleColor }};">{{ $subtitle }}</p>
    @endif
</div>
