<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Placement Test – JavaPath</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #080b1a; color: #e2e8f0; }
        .font-mono { font-family: 'Fira Code', monospace; }
        .bg-grid {
            background-image: linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass { background: rgba(15,17,35,0.9); backdrop-filter: blur(20px); border: 1px solid rgba(99,102,241,0.2); }
        .question-card { transition: all 0.3s ease; }
        .question-card.active { border-color: rgba(99,102,241,0.6) !important; }

        /* Pilihan ganda */
        .option-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(99,102,241,0.15);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .option-card:hover { background: rgba(99,102,241,0.1); border-color: rgba(99,102,241,0.4); }
        .option-card.selected { background: rgba(99,102,241,0.2); border-color: rgba(99,102,241,0.7); }

        /* Urutan kode */
        .code-block {
            background: rgba(0,0,0,0.4);
            border: 1px solid rgba(99,102,241,0.2);
            cursor: grab;
            user-select: none;
            transition: all 0.2s ease;
        }
        .code-block:hover { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.1); }
        .code-block.sortable-ghost { opacity: 0.4; }
        .code-block.sortable-chosen { cursor: grabbing; box-shadow: 0 10px 30px rgba(0,0,0,0.5); transform: scale(1.02); }
        .drag-handle { color: #6366f1; }

        /* Nav dots */
        .q-dot {
            width: 28px; height: 28px;
            border-radius: 50%;
            font-size: 11px; font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid rgba(99,102,241,0.3);
            background: rgba(255,255,255,0.04);
            display: flex; align-items: center; justify-content: center;
        }
        .q-dot.answered { background: rgba(16,185,129,0.3); border-color: rgba(16,185,129,0.6); }
        .q-dot.current { background: rgba(99,102,241,0.5); border-color: #818cf8; }

        /* Timer */
        .timer-warning { color: #fbbf24; }
        .timer-danger  { color: #f87171; animation: pulse 1s infinite; }
        @keyframes pulse { 50% { opacity: 0.5; } }

        /* Tulis kode */
        .code-textarea {
            background: rgba(0,0,0,0.5);
            border: 1px solid rgba(99,102,241,0.25);
            color: #e2e8f0;
            font-family: 'Fira Code', monospace;
            resize: vertical;
            transition: border-color 0.2s;
        }
        .code-textarea:focus { outline: none; border-color: rgba(99,102,241,0.6); box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    </style>
</head>
<body class="min-h-screen bg-grid">

@if(isset($alreadyDone) && $alreadyDone)
    {{-- Already completed --}}
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="glass rounded-2xl p-10 text-center max-w-md">
            <div class="text-5xl mb-4">✅</div>
            <h1 class="text-2xl font-bold text-white mb-2">Placement Test Sudah Selesai</h1>
            <p class="text-gray-400 mb-6">Kamu sudah mengerjakan placement test sebelumnya.</p>
            <a href="{{ route('siswa.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                Ke Dashboard →
            </a>
        </div>
    </div>
@elseif(isset($questions) && $questions->isEmpty())
    {{-- No questions --}}
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="glass rounded-2xl p-10 text-center max-w-md">
            <div class="text-5xl mb-4">📭</div>
            <h1 class="text-2xl font-bold text-white mb-2">Soal Placement Belum Tersedia</h1>
            <p class="text-gray-400 mb-2">Admin belum menambahkan soal placement test.</p>
            <p class="text-gray-500 text-sm mb-6">Hubungi administrator atau coba lagi nanti.</p>
            <form method="POST" action="{{ route('placement.submit') }}">
                @csrf
                <button type="submit" class="px-6 py-3 rounded-xl font-semibold text-white" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    Lewati & Mulai dari Stage 1
                </button>
            </form>
        </div>
    </div>
@else
    {{-- Main Test --}}
    <div class="min-h-screen flex flex-col" x-data="placementTest()" x-init="init()">

        {{-- Top Bar --}}
        <div class="sticky top-0 z-50 glass border-b border-indigo-900/40 px-4 py-3">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <span class="font-bold text-white">JavaPath</span>
                    <span class="text-gray-500 text-sm">/ Placement Test</span>
                </div>
                <div class="flex items-center gap-6">
                    {{-- Timer --}}
                    <div class="flex items-center gap-2 font-mono text-sm" :class="timerClass">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="timerDisplay"></span>
                    </div>
                    {{-- Progress --}}
                    <div class="text-sm text-gray-400">
                        <span class="text-white font-semibold" x-text="answeredCount"></span>/<span>{{ $questions->count() }}</span> dijawab
                    </div>
                </div>
            </div>
            {{-- Progress bar --}}
            <div class="max-w-4xl mx-auto mt-2">
                <div class="w-full h-1 rounded-full" style="background:rgba(99,102,241,0.15);">
                    <div class="h-1 rounded-full transition-all duration-500" style="background:linear-gradient(90deg,#6366f1,#8b5cf6);" :style="`width: ${progressPct}%`"></div>
                </div>
            </div>
        </div>

        <div class="flex flex-1 max-w-4xl mx-auto w-full p-4 gap-4">

            {{-- Question Navigation Sidebar --}}
            <div class="hidden md:block w-40 flex-shrink-0">
                <div class="glass rounded-xl p-3 sticky top-24">
                    <p class="text-xs text-gray-500 mb-2 font-medium">Navigasi Soal</p>
                    <div class="grid grid-cols-4 gap-1">
                        @foreach($questions as $i => $q)
                        <button
                            type="button"
                            @click="goTo({{ $i }})"
                            class="q-dot"
                            :class="{ current: currentIndex === {{ $i }}, answered: isAnswered({{ $i }}) }">
                            {{ $i + 1 }}
                        </button>
                        @endforeach
                    </div>
                    <div class="mt-3 pt-3 border-t border-indigo-900/40 space-y-1">
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <div class="w-3 h-3 rounded-full" style="background:rgba(16,185,129,0.4);"></div> Dijawab
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <div class="w-3 h-3 rounded-full" style="background:rgba(99,102,241,0.5);"></div> Aktif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Questions Area --}}
            <div class="flex-1">
                <form method="POST" action="{{ route('placement.submit') }}" id="placement-form">
                    @csrf

                    @foreach($questions as $i => $question)
                    <div class="question-card glass rounded-2xl p-6 mb-4 border"
                         :class="{ active: currentIndex === {{ $i }}, 'hidden': currentIndex !== {{ $i }} }"
                         x-show="currentIndex === {{ $i }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        {{-- Question header --}}
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:rgba(99,102,241,0.2); color:#818cf8;">
                                        Soal {{ $i + 1 }} dari {{ $questions->count() }}
                                    </span>
                                    @if($question->tipe_soal === 'pilihan_ganda')
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(16,185,129,0.15); color:#6ee7b7;">🔤 Pilihan Ganda</span>
                                    @elseif($question->tipe_soal === 'urutan_kode')
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(251,191,36,0.15); color:#fde68a;">🔀 Urutan Kode</span>
                                    @else
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(99,102,241,0.15); color:#c4b5fd;">⌨️ Tulis Kode</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-lg" style="background:rgba(255,255,255,0.05); color:#64748b;">{{ ucfirst($question->topik) }}</span>
                        </div>

                        {{-- Question text --}}
                        <div class="mb-6">
                            <p class="text-white text-base leading-relaxed whitespace-pre-line">{{ $question->pertanyaan }}</p>
                        </div>

                        {{-- ── PILIHAN GANDA ────────────────────────────────── --}}
                        @if($question->tipe_soal === 'pilihan_ganda')
                        <div class="space-y-3">
                            @foreach(['a' => $question->opsi_a, 'b' => $question->opsi_b, 'c' => $question->opsi_c, 'd' => $question->opsi_d] as $key => $opsi)
                            <label class="option-card flex items-center gap-4 p-4 rounded-xl cursor-pointer"
                                   :class="{ selected: answers[{{ $question->id }}] === '{{ $key }}' }"
                                   @click="selectOption({{ $question->id }}, '{{ $key }}', {{ $i }})">
                                <input type="radio"
                                       name="jawaban[{{ $question->id }}]"
                                       value="{{ $key }}"
                                       class="hidden"
                                       x-ref="radio_{{ $question->id }}_{{ $key }}">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 font-bold text-sm"
                                     :style="answers[{{ $question->id }}] === '{{ $key }}' ? 'background:rgba(99,102,241,0.4); color:#a5b4fc;' : 'background:rgba(255,255,255,0.06); color:#64748b;'">
                                    {{ strtoupper($key) }}
                                </div>
                                <span class="text-gray-200 text-sm leading-relaxed">{{ $opsi }}</span>
                            </label>
                            @endforeach
                        </div>

                        {{-- ── URUTAN KODE ──────────────────────────────────── --}}
                        @elseif($question->tipe_soal === 'urutan_kode')
                        <div>
                            <p class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                                Drag & drop untuk mengurutkan potongan kode yang benar
                            </p>
                            <div class="sortable-list space-y-2"
                                 id="sortable_{{ $question->id }}"
                                 x-init="initSortable({{ $question->id }})">
                                @foreach($question->kode_potongan_shuffled ?? $question->kode_potongan ?? [] as $snippet)
                                <div class="code-block flex items-center gap-3 px-4 py-3 rounded-xl font-mono text-sm"
                                     data-value="{{ $snippet }}">
                                    <svg class="drag-handle w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                    <code class="text-emerald-300 flex-1">{{ $snippet }}</code>
                                </div>
                                @endforeach
                            </div>
                            {{-- Hidden input updated by JS --}}
                            <input type="hidden"
                                   name="jawaban[{{ $question->id }}]"
                                   id="order_{{ $question->id }}"
                                   x-ref="order_{{ $question->id }}">
                        </div>

                        {{-- ── TULIS KODE ───────────────────────────────────── --}}
                        @else
                        <div>
                            <p class="text-xs text-gray-500 mb-3 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Ketik output yang dihasilkan oleh kode di atas
                            </p>
                            <textarea
                                name="jawaban[{{ $question->id }}]"
                                rows="4"
                                class="code-textarea w-full px-4 py-3 rounded-xl text-sm"
                                placeholder="Tulis output di sini..."
                                @input="markAnswered({{ $i }})">{{ old("jawaban.{$question->id}") }}</textarea>
                        </div>
                        @endif

                    </div>
                    @endforeach

                    {{-- Navigation buttons --}}
                    <div class="flex items-center justify-between mt-4">
                        <button type="button"
                            @click="prev()"
                            x-show="currentIndex > 0"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium"
                            style="background:rgba(255,255,255,0.06); color:#94a3b8; border:1px solid rgba(99,102,241,0.2);">
                            ← Sebelumnya
                        </button>
                        <div x-show="currentIndex === 0" class="w-1"></div>

                        <button type="button"
                            @click="next()"
                            x-show="currentIndex < {{ $questions->count() - 1 }}"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                            Selanjutnya →
                        </button>

                        {{-- Submit button on last question --}}
                        <button type="button"
                            @click="confirmSubmit()"
                            x-show="currentIndex === {{ $questions->count() - 1 }}"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background:linear-gradient(135deg,#059669,#10b981);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesai & Kumpulkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Submit Confirmation Modal --}}
        <div x-show="showConfirm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background:rgba(0,0,0,0.7); backdrop-filter:blur(8px);">
            <div class="glass rounded-2xl p-8 max-w-sm w-full text-center" @click.stop>
                <div class="text-4xl mb-3">📋</div>
                <h3 class="text-xl font-bold text-white mb-2">Kumpulkan Jawaban?</h3>
                <p class="text-gray-400 text-sm mb-1">Soal dijawab: <span class="text-white font-semibold" x-text="answeredCount"></span> dari <strong>{{ $questions->count() }}</strong></p>
                <p class="text-gray-500 text-xs mb-6">Setelah dikumpulkan, jawaban tidak bisa diubah.</p>
                <div class="flex gap-3">
                    <button @click="showConfirm = false" class="flex-1 py-2.5 rounded-xl text-sm font-medium" style="background:rgba(255,255,255,0.06); color:#94a3b8;">
                        Batal
                    </button>
                    <button @click="doSubmit()" class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:linear-gradient(135deg,#059669,#10b981);">
                        Ya, Kumpulkan!
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
    function placementTest() {
        return {
            currentIndex: 0,
            answers: {},
            answeredQuestions: new Set(),
            showConfirm: false,
            timer: 30 * 60, // 30 menit
            timerInterval: null,

            get timerDisplay() {
                const m = Math.floor(this.timer / 60).toString().padStart(2, '0');
                const s = (this.timer % 60).toString().padStart(2, '0');
                return `${m}:${s}`;
            },

            get timerClass() {
                if (this.timer < 300) return 'timer-danger';
                if (this.timer < 600) return 'timer-warning';
                return 'text-gray-300';
            },

            get progressPct() {
                return (this.answeredQuestions.size / {{ $questions->count() }}) * 100;
            },

            get answeredCount() {
                return this.answeredQuestions.size;
            },

            init() {
                this.timerInterval = setInterval(() => {
                    this.timer--;
                    if (this.timer <= 0) {
                        clearInterval(this.timerInterval);
                        this.doSubmit();
                    }
                }, 1000);
            },

            goTo(index) {
                this.currentIndex = index;
            },

            prev() {
                if (this.currentIndex > 0) this.currentIndex--;
            },

            next() {
                if (this.currentIndex < {{ $questions->count() - 1 }}) this.currentIndex++;
            },

            selectOption(questionId, value, index) {
                this.answers[questionId] = value;
                this.answeredQuestions.add(index);
                // Check the actual radio
                const radio = document.querySelector(`input[name="jawaban[${questionId}]"][value="${value}"]`);
                if (radio) radio.checked = true;
            },

            markAnswered(index) {
                this.answeredQuestions.add(index);
            },

            isAnswered(index) {
                return this.answeredQuestions.has(index);
            },

            initSortable(questionId) {
                const el = document.getElementById(`sortable_${questionId}`);
                if (!el) return;
                const updateOrder = () => {
                    const items = el.querySelectorAll('[data-value]');
                    const order = Array.from(items).map(i => i.dataset.value);
                    const input = document.getElementById(`order_${questionId}`);
                    if (input) input.value = order.join(',');
                    // Mark as answered
                    const qIndex = parseInt(el.closest('.question-card')?.dataset?.index ?? -1);
                };
                Sortable.create(el, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    handle: '.drag-handle',
                    onEnd: () => updateOrder(),
                });
                // Set initial order
                updateOrder();
            },

            confirmSubmit() {
                this.showConfirm = true;
            },

            doSubmit() {
                this.showConfirm = false;
                clearInterval(this.timerInterval);
                // Update all sortable hidden inputs before submit
                document.querySelectorAll('.sortable-list').forEach(el => {
                    const id = el.id.replace('sortable_', '');
                    const items = el.querySelectorAll('[data-value]');
                    const order = Array.from(items).map(i => i.dataset.value);
                    const input = document.getElementById(`order_${id}`);
                    if (input) input.value = order.join(',');
                });
                document.getElementById('placement-form').submit();
            },
        }
    }
    </script>
@endif
</body>
</html>
