<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JavaPath') }} – @yield('page-title', 'Auth')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
            .font-mono { font-family: 'Fira Code', monospace; }

            .bg-grid {
                background-image:
                    linear-gradient(rgba(99,102,241,0.07) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(99,102,241,0.07) 1px, transparent 1px);
                background-size: 40px 40px;
            }

            .glow-indigo {
                box-shadow: 0 0 40px rgba(99,102,241,0.25), 0 0 80px rgba(99,102,241,0.1);
            }

            .glass-card {
                background: rgba(15, 17, 35, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(99,102,241,0.2);
            }

            .input-field {
                background: rgba(255,255,255,0.05);
                border: 1px solid rgba(99,102,241,0.25);
                color: #e2e8f0;
                transition: all 0.2s ease;
            }
            .input-field:focus {
                background: rgba(99,102,241,0.08);
                border-color: rgba(99,102,241,0.6);
                outline: none;
                box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            }
            .input-field::placeholder { color: #64748b; }

            .btn-primary {
                background: linear-gradient(135deg, #6366f1, #8b5cf6);
                transition: all 0.2s ease;
                position: relative;
                overflow: hidden;
            }
            .btn-primary::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, #818cf8, #a78bfa);
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            .btn-primary:hover::after { opacity: 1; }
            .btn-primary span { position: relative; z-index: 1; }
            .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(99,102,241,0.4); }
            .btn-primary:active { transform: translateY(0); }

            .floating-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                animation: float 8s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-20px) scale(1.05); }
            }

            .code-tag {
                animation: blink 1.2s step-end infinite;
            }
            @keyframes blink { 50% { opacity: 0; } }

            .label-text {
                color: #94a3b8;
                font-size: 0.8125rem;
                font-weight: 500;
                letter-spacing: 0.025em;
            }

            .slide-up {
                animation: slideUp 0.5s ease forwards;
            }
            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="antialiased" style="background-color: #080b1a; min-height: 100vh;">

        <!-- Background -->
        <div class="fixed inset-0 bg-grid pointer-events-none"></div>
        <div class="floating-orb w-96 h-96 top-[-100px] left-[-100px]" style="background: rgba(99,102,241,0.15);"></div>
        <div class="floating-orb w-80 h-80 bottom-[-80px] right-[-80px]" style="background: rgba(139,92,246,0.12); animation-delay: 3s;"></div>
        <div class="floating-orb w-64 h-64 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" style="background: rgba(16,185,129,0.06); animation-delay: 1.5s;"></div>

        <!-- Main content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md slide-up">

                <!-- Logo & Brand -->
                <div class="text-center mb-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center glow-indigo" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="text-2xl font-bold text-white tracking-tight">Java<span style="color:#818cf8;">Path</span></span>
                            <p class="text-xs font-mono" style="color:#64748b;">adaptive learning platform</p>
                        </div>
                    </a>
                </div>

                <!-- Card -->
                <div class="glass-card rounded-2xl p-8 glow-indigo">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <p class="text-center mt-6 text-xs" style="color:#374151;">
                    © {{ date('Y') }} JavaPath. Belajar Java secara adaptif.
                </p>
            </div>
        </div>
    </body>
</html>
