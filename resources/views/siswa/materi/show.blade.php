@extends('layouts.siswa')

@section('title', $material->judul . ' – JavaPath')

@section('extra-head')
    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('extra-styles')
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap');

    body {
        background-color: #0F1225;
        color: #e2e8f0;
    }

    .materi-container { 
        font-family: 'Nunito', sans-serif; 
        max-width: 100%; 
        overflow-x: hidden;
    }
    
    /* Interactive Card Styles */
    .materi-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(124, 92, 252, 0.2);
        border-radius: 1.5rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(12px);
        transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .materi-card:hover {
        border-color: rgba(124, 92, 252, 0.5);
        box-shadow: 0 15px 40px -10px rgba(124, 92, 252, 0.2);
        transform: translateY(-2px);
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-bottom: 1px solid rgba(124, 92, 252, 0.2);
        padding-bottom: 0.75rem;
    }
    .card-title svg {
        color: #7C5CFC;
        width: 1.5rem;
        height: 1.5rem;
    }

    /* Prose / Typography inside cards */
    .card-content p {
        margin-bottom: 1.2rem;
        line-height: 1.8;
        font-size: 1.05rem;
        color: #cbd5e1;
    }
    .card-content ul {
        list-style-type: none;
        padding-left: 0;
        margin-bottom: 1.2rem;
    }
    .card-content li {
        position: relative;
        padding-left: 1.75rem;
        margin-bottom: 0.75rem;
        line-height: 1.6;
        color: #cbd5e1;
    }
    .card-content li::before {
        content: '→';
        position: absolute;
        left: 0;
        color: #7C5CFC;
        font-weight: bold;
    }

    /* Callouts */
    .callout {
        padding: 1.25rem 1.5rem;
        border-radius: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        font-size: 1.05rem;
        line-height: 1.6;
    }
    .callout-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    .callout.tips { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); color: #bfdbfe; }
    .callout.warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #fde68a; }
    .callout.note { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #a7f3d0; }
    .callout.target { background: rgba(124, 92, 252, 0.1); border: 1px solid rgba(124, 92, 252, 0.3); color: #e0e7ff; }

    /* Analogi Layout */
    .analogi-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        align-items: center;
    }
    @media (min-width: 768px) {
        .analogi-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    .analogi-image-wrapper {
        background: rgba(124, 92, 252, 0.05);
        border: 1px dashed rgba(124, 92, 252, 0.3);
        border-radius: 1.5rem;
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .analogi-image-wrapper img {
        max-width: 100%;
        border-radius: 1rem;
    }

    /* Code Blocks */
    .prose-content pre, .card-content pre { 
        background: #1e1e1e; /* VS Code dark background */
        padding: 2.75rem 0 0 0; 
        border-radius: 12px; 
        overflow: hidden; 
        margin-bottom: 1.5rem; 
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5), 0 2px 4px -1px rgba(0, 0, 0, 0.3); 
        position: relative; 
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .prose-content pre:hover, .card-content pre:hover {
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
    }
    
    .code-wrapper {
        display: flex;
        overflow-x: auto;
        padding: 1rem 0;
        background: #1e1e1e;
        scrollbar-width: thin;
        scrollbar-color: #4b5563 transparent;
    }
    .code-wrapper::-webkit-scrollbar {
        height: 8px;
    }
    .code-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }
    .code-wrapper::-webkit-scrollbar-thumb {
        background-color: #4b5563;
        border-radius: 20px;
    }
    
    .line-numbers {
        display: flex;
        flex-direction: column;
        padding: 0 1rem 0 0.5rem;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        color: #858585;
        text-align: right;
        user-select: none;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.9em;
        line-height: 1.6;
        min-width: 2.5rem;
    }

    .prose-content pre code, .card-content pre code { 
        background: none !important; 
        padding: 0 1.5rem !important; 
        border: none !important; 
        font-size: 0.95em !important; 
        font-family: 'JetBrains Mono', monospace !important; 
        white-space: pre; 
        color: #9cdcfe !important; /* VS Code variable text as default */
        line-height: 1.6;
    }
    
    /* VS Code-like syntax highlighting overrides for atom-one-dark */
    .hljs-keyword { color: #569cd6 !important; }
    .hljs-string { color: #ce9178 !important; }
    .hljs-number { color: #b5cea8 !important; }
    .hljs-type, .hljs-title.class_, .hljs-built_in { color: #4ec9b0 !important; }
    .hljs-title.function_ { color: #dcdcaa !important; }
    .hljs-literal { color: #569cd6 !important; } /* true, false, null */
    .hljs-comment { color: #6a9955 !important; }
    .hljs-variable, .hljs-property, .hljs-attr { color: #9cdcfe !important; }
    .hljs-punctuation, .hljs-operator { color: #d4d4d4 !important; }
    
    .pre-header { 
        position: absolute; 
        top: 0; 
        left: 0; 
        right: 0; 
        height: 3rem; 
        background: #1e1e1e; 
        border-bottom: 1px solid rgba(255,255,255,0.05); 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 0 1rem;
        box-sizing: border-box;
    }
    .mac-dots { display: flex; gap: 6px; }
    .mac-dots span { width: 12px; height: 12px; border-radius: 50%; border: 1px solid rgba(0,0,0,0.3); }
    .mac-dots span:nth-child(1) { background: #ff5f56; }
    .mac-dots span:nth-child(2) { background: #ffbd2e; }
    .mac-dots span:nth-child(3) { background: #27c93f; }
    
    .java-badge { 
        font-family: 'Inter', sans-serif; 
        font-size: 0.8rem; 
        color: #a1a1aa; 
        position: absolute; 
        left: 50%; 
        transform: translateX(-50%); 
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    /* Inline Code */
    .card-content code:not(pre code) { 
        background: rgba(124, 92, 252, 0.15); 
        padding: 0.2em 0.4em; 
        border-radius: 4px; 
        font-family: 'JetBrains Mono', monospace; 
        font-size: 0.9em; 
        color: #c4b5fd; 
    }

    /* Quiz Button */
    .quiz-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #e2e8f0;
        transition: all 0.2s;
        cursor: pointer;
    }
    .quiz-btn:hover {
        background: rgba(124, 92, 252, 0.1);
        border-color: rgba(124, 92, 252, 0.4);
    }
    .quiz-btn.selected {
        background: rgba(124, 92, 252, 0.2);
        border-color: #7C5CFC;
    }
    .quiz-btn.correct {
        background: rgba(16, 185, 129, 0.15);
        border-color: #10b981;
        color: #34d399;
    }
    .quiz-btn.wrong {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #f87171;
    }

    #raw-materi-content { display: none; }
@endsection

@section('content')
    <div class="materi-container">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 flex-wrap font-sans">
            <a href="{{ route('siswa.dashboard') }}" class="hover:text-indigo-400 transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <span class="text-gray-600">/</span>
            <a href="{{ route('siswa.stage.show', $stage) }}" class="hover:text-indigo-400 transition">Stage {{ $stage->urutan }}</a>
            <span class="text-gray-600">/</span>
            <span class="text-indigo-300 font-medium">{{ Str::limit($material->judul, 30) }}</span>
        </div>

        {{-- Hero Section / Materi Header --}}
        <div class="materi-card !p-8 md:!p-12 relative overflow-hidden" style="border-color: rgba(124, 92, 252, 0.4); background: linear-gradient(145deg, rgba(30, 31, 56, 0.9) 0%, rgba(15, 18, 37, 0.95) 100%);">
            <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            <div class="absolute -bottom-8 -left-8 w-64 h-64 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            
            <div class="relative z-10">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <span class="text-xs px-4 py-1.5 rounded-full font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        Stage {{ $stage->urutan }} • Materi {{ $material->urutan }}
                    </span>
                    <span class="text-sm font-medium text-gray-400 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Estimasi ~5 Menit
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-8">
                    {{ $material->judul }}
                </h1>

                <div class="bg-black/20 rounded-xl p-5 border border-white/5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-300">Progress Belajar</span>
                        <span class="text-sm font-bold text-indigo-400">{{ $isCompleted ? '100%' : '0%' }}</span>
                    </div>
                    <div class="h-2 w-full bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-1000" style="width: {{ $isCompleted ? '100%' : '0%' }};"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden Raw Content from Database --}}
        <div id="raw-materi-content">
            {!! $material->konten !!}
        </div>

        {{-- Dynamic Cards Container --}}
        <div id="materi-cards-container">
            <!-- Cards will be generated here by JavaScript -->
        </div>

        {{-- Navigation between materials --}}
        <div class="flex items-center justify-between gap-4 mt-8">
            @if($prevMaterial)
            <a href="{{ route('siswa.materi.show', $prevMaterial) }}" class="nav-btn flex-1 p-4 rounded-xl flex items-center gap-3" style="background:rgba(255,255,255,0.02); border:1px solid rgba(124, 92, 252, 0.15);">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Sebelumnya</p>
                    <p class="text-sm font-medium text-gray-300 truncate">{{ $prevMaterial->judul }}</p>
                </div>
            </a>
            @else
            <div class="flex-1"></div>
            @endif

            @if($nextMaterial)
            <a href="{{ route('siswa.materi.show', $nextMaterial) }}" class="nav-btn flex-1 p-4 rounded-xl flex items-center justify-end gap-3 text-right" style="background:rgba(255,255,255,0.02); border:1px solid rgba(124, 92, 252, 0.15);">
                <div class="min-w-0">
                    <p class="text-xs text-gray-500">Selanjutnya</p>
                    <p class="text-sm font-medium text-gray-300 truncate">{{ $nextMaterial->judul }}</p>
                </div>
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @else
            <form method="POST" action="{{ route('siswa.materi.done', $material) }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="nav-btn w-full p-4 rounded-xl flex items-center justify-end gap-3 text-right"
                    style="background: {{ $isCompleted ? 'rgba(16,185,129,0.1)' : 'rgba(16,185,129,0.05)' }}; border:1px solid rgba(16,185,129,0.3); transition: all 0.3s;">
                    <div class="min-w-0">
                        <p class="text-xs text-gray-500">{{ $isCompleted ? 'Sudah selesai' : 'Tandai selesai' }}</p>
                        <p class="text-sm font-medium text-emerald-400 truncate">Kembali ke Stage</p>
                    </div>
                    @if($isCompleted)
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Parser Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const rawContent = document.getElementById('raw-materi-content');
            const container = document.getElementById('materi-cards-container');
            if(!rawContent || !container) return;

            // Helper to create an icon SVG
            const getIcon = (type) => {
                const icons = {
                    'analogi': '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    'quiz': '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                    'default': '<svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>'
                };
                return icons[type] || icons['default'];
            };

            // Process nodes to group them by H2
            const nodes = Array.from(rawContent.childNodes);
            let currentCardNodes = [];
            const cards = [];

            nodes.forEach(node => {
                if (node.nodeName.toLowerCase() === 'h2') {
                    if (currentCardNodes.length > 0) {
                        cards.push(currentCardNodes);
                    }
                    currentCardNodes = [node];
                } else if (node.nodeType === 1 || (node.nodeType === 3 && node.textContent.trim() !== '')) {
                    // Ignore empty text nodes
                    currentCardNodes.push(node);
                }
            });
            if (currentCardNodes.length > 0) {
                cards.push(currentCardNodes);
            }

            // If there's no H2 at all, wrap everything in one card
            if (cards.length > 0 && cards[0][0] && cards[0][0].nodeName.toLowerCase() !== 'h2') {
                const title = document.createElement('h2');
                title.textContent = 'Materi';
                cards[0].unshift(title);
            }

            // Render Cards
            cards.forEach((cardNodes, index) => {
                const titleNode = cardNodes.shift(); // The H2
                let titleText = titleNode ? titleNode.textContent : 'Bagian ' + (index + 1);
                
                // Determine Card Type
                const isAnalogi = titleText.toLowerCase().includes('analogi');
                const isQuiz = titleText.toLowerCase().includes('coba tebak') || titleText.toLowerCase().includes('kuis') || titleText.toLowerCase().includes('latihan');
                let iconType = 'default';
                if (isAnalogi) iconType = 'analogi';
                if (isQuiz) iconType = 'quiz';

                const cardEl = document.createElement('div');
                cardEl.className = 'materi-card';
                
                // Title
                const titleEl = document.createElement('h2');
                titleEl.className = 'card-title';
                titleEl.innerHTML = `${getIcon(iconType)} ${titleText}`;
                cardEl.appendChild(titleEl);

                // Content wrapper
                const contentEl = document.createElement('div');
                contentEl.className = 'card-content';

                // Append remaining nodes
                cardNodes.forEach(n => {
                    const clone = n.cloneNode(true);
                    contentEl.appendChild(clone);
                });

                // Post-process contentEl
                processContent(contentEl, isAnalogi, isQuiz, index);

                cardEl.appendChild(contentEl);
                container.appendChild(cardEl);
            });

            // Process specific elements inside a card
            function processContent(el, isAnalogi, isQuiz, cardIndex) {
                // Handle Callouts (💡, ⚠, 📌, 🎯)
                const paragraphs = el.querySelectorAll('p');
                paragraphs.forEach(p => {
                    const text = p.textContent.trim();
                    let calloutType = '';
                    let icon = '';
                    
                    if (text.startsWith('💡')) { calloutType = 'tips'; icon = '💡'; }
                    else if (text.startsWith('⚠')) { calloutType = 'warning'; icon = '⚠'; }
                    else if (text.startsWith('📌')) { calloutType = 'note'; icon = '📌'; }
                    else if (text.startsWith('🎯')) { calloutType = 'target'; icon = '🎯'; }

                    if (calloutType) {
                        const calloutDiv = document.createElement('div');
                        calloutDiv.className = `callout ${calloutType}`;
                        // Remove the icon from the text body
                        const cleanText = p.innerHTML.replace(icon, '').trim();
                        calloutDiv.innerHTML = `<div class="callout-icon">${icon}</div><div>${cleanText}</div>`;
                        p.parentNode.replaceChild(calloutDiv, p);
                    }
                });

                // Handle Analogi Layout
                if (isAnalogi) {
                    const imgs = el.querySelectorAll('img');
                    if (imgs.length > 0) {
                        const img = imgs[0];
                        const wrapper = document.createElement('div');
                        wrapper.className = 'analogi-grid';
                        
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'analogi-image-wrapper';
                        imgWrapper.appendChild(img.cloneNode(true));
                        
                        const textWrapper = document.createElement('div');
                        // Move everything else into textWrapper
                        while(el.firstChild) {
                            if (el.firstChild === img || (el.firstChild.querySelector && el.firstChild.querySelector('img') === img)) {
                                el.removeChild(el.firstChild);
                            } else {
                                textWrapper.appendChild(el.firstChild);
                            }
                        }
                        
                        wrapper.appendChild(imgWrapper);
                        wrapper.appendChild(textWrapper);
                        el.appendChild(wrapper);
                    }
                }

                // Handle Mini Quiz Layout
                if (isQuiz) {
                    const lists = el.querySelectorAll('ul, ol');
                    if (lists.length > 0) {
                        const list = lists[0];
                        const options = [];
                        let correctIndex = -1;

                        Array.from(list.children).forEach((li, idx) => {
                            let text = li.innerHTML;
                            // check if answer is bolded or has asterisk
                            if (text.includes('<strong>') || text.includes('<b>') || text.includes('*')) {
                                correctIndex = idx;
                                text = text.replace(/<\/?strong>/g, '').replace(/<\/?b>/g, '').replace(/\*/g, '').trim();
                            }
                            // check raw text just in case
                            if(li.textContent.includes('*')) {
                                correctIndex = idx;
                                text = li.innerHTML.replace(/\*/g, '').trim();
                            }
                            options.push(text);
                        });

                        if (correctIndex === -1) correctIndex = 0; // fallback

                        const quizId = 'quiz_' + cardIndex;
                        
                        const quizHtml = `
                            <div x-data="{
                                selected: null,
                                checked: false,
                                correct: ${correctIndex},
                                checkAnswer() {
                                    if(this.selected !== null) this.checked = true;
                                }
                            }" class="mt-4">
                                <div class="space-y-2 mb-4">
                                    ${options.map((opt, idx) => `
                                        <button @click="if(!checked) selected = ${idx}" 
                                                :class="{
                                                    'selected': selected === ${idx} && !checked,
                                                    'correct': checked && ${idx} === correct,
                                                    'wrong': checked && selected === ${idx} && selected !== correct,
                                                    'opacity-50 cursor-not-allowed': checked && ${idx} !== correct && selected !== ${idx}
                                                }"
                                                class="quiz-btn">
                                            <div class="flex items-center gap-3">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                                     :class="selected === ${idx} || (checked && ${idx} === correct) ? 'border-current' : 'border-gray-500'">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-current" x-show="selected === ${idx} || (checked && ${idx} === correct)"></div>
                                                </div>
                                                <span>${opt}</span>
                                            </div>
                                        </button>
                                    `).join('')}
                                </div>
                                
                                <button @click="checkAnswer()" 
                                        x-show="!checked"
                                        :disabled="selected === null"
                                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition-colors">
                                    Cek Jawaban
                                </button>
                                
                                <div x-show="checked" x-transition class="p-4 rounded-xl mt-4" :class="selected === correct ? 'bg-emerald-500/20 border border-emerald-500/30' : 'bg-red-500/20 border border-red-500/30'">
                                    <p class="font-bold flex items-center gap-2" :class="selected === correct ? 'text-emerald-400' : 'text-red-400'">
                                        <svg x-show="selected === correct" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <svg x-show="selected !== correct" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span x-text="selected === correct ? 'Benar Sekali! 🎉' : 'Kurang Tepat, coba pelajari lagi. 💡'"></span>
                                    </p>
                                </div>
                            </div>
                        `;

                        const quizContainer = document.createElement('div');
                        quizContainer.innerHTML = quizHtml;
                        list.parentNode.replaceChild(quizContainer, list);
                    }
                }

                // Handle Code Blocks (Format like VS Code)
                const preElements = el.querySelectorAll('pre');
preElements.forEach((pre) => {
    // --- NORMALISASI: lepas <pre> dari wrapper asing & buang semua child selain <code> ---
    const parent = pre.parentElement;
    if (parent && parent !== el && parent.tagName !== 'LI') {
        // Pindahkan <pre> keluar dari wrapper toolbar bawaan Filament, lalu buang wrapper-nya
        parent.parentNode.insertBefore(pre, parent);
        parent.remove();
    }
    // Buang semua elemen di dalam <pre> selain <code> (sisa toolbar/header lama)
    Array.from(pre.children).forEach(child => {
        if (child.tagName !== 'CODE') {
            child.remove();
        }
    });
    // --- END NORMALISASI ---

    const code = pre.querySelector('code');
    const codeText = code ? code.innerText : pre.innerText;

    if (code && !code.className.includes('language-')) {
        code.classList.add('language-java'); // default to java
    }

    // VS Code Header
    const header = document.createElement('div');
    header.className = 'pre-header';
    
    const leftDiv = document.createElement('div');
    leftDiv.className = 'mac-dots';
    leftDiv.innerHTML = `<span></span><span></span><span></span>`;
    
    // Center div removed as requested

    
    const copyBtn = document.createElement('button');
    // Using inline styles to ensure it looks perfect even if Tailwind misses the classes
    copyBtn.style.cssText = 'display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #9ca3af; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1); padding: 4px 10px; border-radius: 6px; font-family: sans-serif; font-weight: 500; background: transparent; cursor: pointer;';
    
    // Add hover effect via JS since it's inline
    copyBtn.onmouseenter = () => {
        copyBtn.style.color = '#ffffff';
        copyBtn.style.borderColor = 'rgba(255,255,255,0.2)';
        copyBtn.style.backgroundColor = 'rgba(255,255,255,0.05)';
    };
    copyBtn.onmouseleave = () => {
        copyBtn.style.color = '#9ca3af';
        copyBtn.style.borderColor = 'rgba(255,255,255,0.1)';
        copyBtn.style.backgroundColor = 'transparent';
    };

    copyBtn.innerHTML = `
        <svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
        <span>Copy</span>
    `;
    copyBtn.onclick = () => copyCode(copyBtn, codeText);
    
    header.appendChild(leftDiv);
    header.appendChild(copyBtn);
    
    pre.insertBefore(header, pre.firstChild);

    if (code) {
        hljs.highlightElement(code);
        
        // Line numbers
        const linesCount = code.innerHTML.split('\n').length;
        let lineNumbersHtml = '';
        for (let i = 1; i <= linesCount; i++) {
            lineNumbersHtml += `<span>${i}</span>`;
        }
        
        const codeWrapper = document.createElement('div');
        codeWrapper.className = 'code-wrapper';
        
        const lineNumbersDiv = document.createElement('div');
        lineNumbersDiv.className = 'line-numbers';
        lineNumbersDiv.innerHTML = lineNumbersHtml;
        
        pre.insertBefore(codeWrapper, code);
        codeWrapper.appendChild(lineNumbersDiv);
        codeWrapper.appendChild(code);
    }
});
            }
        });

        // Global function for copying code
        function copyCode(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = `<svg style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> <span>Copied!</span>`;
                btn.style.color = '#34d399'; // green-400
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.style.color = '#ffffff';
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }
    </script>
@endsection
