<x-guest-layout>
@section('page-title', 'Masuk')

    <!-- Header -->
    <div class="mb-7">
        <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang Kembali!</h1>
        <p class="text-sm" style="color:#64748b;">
            Masuk untuk melanjutkan belajar Java-mu
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="color:#6ee7b7; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3);">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" id="login-form">
        @csrf

        <!-- Username -->
        <div>
            <label for="username" class="label-text block mb-1.5">
                Username
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                    </svg>
                </div>
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan username"
                    class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm font-mono"
                >
            </div>
            @error('username')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="label-text">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-medium hover:underline transition-colors"
                        style="color:#818cf8;">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="input-field w-full pl-10 pr-11 py-3 rounded-xl text-sm"
                >
                <button type="button" onclick="togglePwd('password', this)"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    style="color:#64748b;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2">
            <input
                id="remember_me"
                name="remember"
                type="checkbox"
                class="w-4 h-4 rounded"
                style="accent-color:#6366f1; background:rgba(255,255,255,0.05); border-color:rgba(99,102,241,0.4);"
            >
            <label for="remember_me" class="text-sm select-none cursor-pointer" style="color:#94a3b8;">
                Ingat saya
            </label>
        </div>

        <!-- Submit -->
        <button type="submit"
            class="btn-primary w-full py-3 rounded-xl text-sm font-semibold text-white"
            id="submit-btn">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Masuk Sekarang
            </span>
        </button>

        <!-- Divider -->
        <div class="relative my-2">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t" style="border-color:rgba(99,102,241,0.2);"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 text-gray-500" style="background:#0f1123;">atau</span>
            </div>
        </div>

        <!-- Register link -->
        <p class="text-center text-sm" style="color:#64748b;">
            Belum punya akun?
            <a href="{{ route('register') }}"
                class="font-semibold hover:underline ml-1 transition-colors"
                style="color:#818cf8;">
                Daftar gratis
            </a>
        </p>
    </form>

    <!-- Code snippet decoration -->
    <div class="mt-6 px-4 py-3 rounded-xl font-mono text-xs" style="background:rgba(0,0,0,0.3); border:1px solid rgba(99,102,241,0.15);">
        <span style="color:#64748b;">// </span>
        <span style="color:#6ee7b7;">System</span><span style="color:#94a3b8;">.</span><span style="color:#818cf8;">out</span><span style="color:#94a3b8;">.</span><span style="color:#fbbf24;">println</span><span style="color:#94a3b8;">(</span><span style="color:#f472b6;">"Selamat belajar Java!"</span><span style="color:#94a3b8;">)</span><span class="code-tag" style="color:#94a3b8;">|</span>
    </div>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.style.color = input.type === 'text' ? '#818cf8' : '#64748b';
        }
    </script>
</x-guest-layout>
