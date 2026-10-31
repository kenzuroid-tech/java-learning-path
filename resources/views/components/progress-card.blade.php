@props(['progress'])

<div class="glass rounded-2xl p-5 mb-6">
    <h2 class="text-sm font-semibold text-gray-300 mb-4">Progress Stage</h2>
    <div class="grid grid-cols-3 gap-4">
        <div class="flex items-center gap-3 p-3 rounded-xl" style="background: {{ optional($progress)->materi_selesai ? 'rgba(16,185,129,0.08)' : 'rgba(255,255,255,0.02)' }}; border: 1px solid {{ optional($progress)->materi_selesai ? 'rgba(16,185,129,0.25)' : 'rgba(255,255,255,0.06)' }};">
            <div class="progress-dot {{ optional($progress)->materi_selesai ? 'done' : 'pending' }}"></div>
            <div>
                <p class="text-sm font-medium {{ optional($progress)->materi_selesai ? 'text-emerald-400' : 'text-gray-400' }}">Materi</p>
                <p class="text-xs text-gray-500">{{ optional($progress)->materi_selesai ? 'Selesai' : 'Belum selesai' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl" style="background: {{ optional($progress)->kuis_selesai ? 'rgba(16,185,129,0.08)' : 'rgba(255,255,255,0.02)' }}; border: 1px solid {{ optional($progress)->kuis_selesai ? 'rgba(16,185,129,0.25)' : 'rgba(255,255,255,0.06)' }};">
            <div class="progress-dot {{ optional($progress)->kuis_selesai ? 'done' : 'pending' }}"></div>
            <div>
                <p class="text-sm font-medium {{ optional($progress)->kuis_selesai ? 'text-emerald-400' : 'text-gray-400' }}">Kuis</p>
                <p class="text-xs text-gray-500">{{ optional($progress)->kuis_selesai ? 'Nilai: ' . number_format(optional($progress)->kuis_nilai, 0) : 'Belum dikerjakan' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl" style="background: {{ optional($progress)->praktikum_selesai ? 'rgba(16,185,129,0.08)' : 'rgba(255,255,255,0.02)' }}; border: 1px solid {{ optional($progress)->praktikum_selesai ? 'rgba(16,185,129,0.25)' : 'rgba(255,255,255,0.06)' }};">
            <div class="progress-dot {{ optional($progress)->praktikum_selesai ? 'done' : 'pending' }}"></div>
            <div>
                <p class="text-sm font-medium {{ optional($progress)->praktikum_selesai ? 'text-emerald-400' : 'text-gray-400' }}">Praktikum</p>
                <p class="text-xs text-gray-500">{{ optional($progress)->praktikum_selesai ? 'Selesai' : 'Belum dikerjakan' }}</p>
            </div>
        </div>
    </div>
</div>
