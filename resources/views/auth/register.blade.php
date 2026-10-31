<x-guest-layout>
@section('page-title', 'Daftar Akun')

    <!-- Header -->
    <div class="mb-7">
        <h1 class="text-2xl font-bold text-white mb-1">Buat Akun Baru</h1>
        <p class="text-sm" style="color:#64748b;">
            Mulai perjalanan belajar Java-mu sekarang
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium text-emerald-300" style="background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3);">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-5" id="register-form">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="label-text block mb-1.5">
                Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nama lengkapmu"
                    class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm"
                >
            </div>
            @error('name')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

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
                    autocomplete="username"
                    placeholder="username_kamu"
                    class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm font-mono"
                >
            </div>
            @error('username')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="label-text block mb-1.5">
                Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    placeholder="email@contoh.com"
                    class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-sm"
                >
            </div>
            @error('email')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="label-text block mb-1.5">
                Password
            </label>
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
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="label-text block mb-1.5">
                Konfirmasi Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4" style="color:#6366f1;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    class="input-field w-full pl-10 pr-11 py-3 rounded-xl text-sm"
                >
                <button type="button" onclick="togglePwd('password_confirmation', this)"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                    style="color:#64748b;">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1.5 text-xs" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit"
            class="btn-primary w-full py-3 rounded-xl text-sm font-semibold text-white mt-2"
            id="submit-btn">
            <span class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Daftar Sekarang
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

        <!-- Login link -->
        <p class="text-center text-sm" style="color:#64748b;">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="font-semibold hover:underline ml-1 transition-colors"
                style="color:#818cf8;">
                Masuk di sini
            </a>
        </p>
    </form>

    <script>
        function togglePwd(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.style.color = input.type === 'text' ? '#818cf8' : '#64748b';
        }
    </script>
</x-guest-layout>
