<section>
    <header class="flex flex-col sm:flex-row sm:items-start gap-4 pb-6 border-b border-sky-100">
        <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-100 to-sky-50 flex items-center justify-center shadow-sm">
            <svg class="w-7 h-7 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800">
                Informasi Profil
            </h2>
            <p class="mt-1.5 text-sm text-slate-500 leading-relaxed">
                Perbarui nama pengguna dan alamat email akun Anda.
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profileForm" method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-6">
        @csrf
        @method('patch')

        <!-- Username -->
        <div class="space-y-2">
            <label for="username" class="block text-sm font-semibold text-slate-700">
                Username
            </label>

            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username', $user->username) }}"
                required
                autofocus
                autocomplete="username"
                placeholder="nama_pengguna_anda"
                class="w-full px-4 py-3.5 bg-slate-50/50 border-2 border-slate-200 rounded-xl
                       text-slate-800 placeholder-slate-400 font-medium
                       focus:outline-none focus:border-sky-500 focus:bg-white transition-all"
            />

            <p class="text-xs text-slate-400">
                Hanya huruf, angka, dan garis bawah yang diperbolehkan.
            </p>

            @error('username')
                <p class="text-sm text-red-500 flex items-center gap-1.5 mt-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-semibold text-slate-700">
                Alamat Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="email"
                placeholder="email@contoh.com"
                class="w-full px-4 py-3.5 bg-slate-50/50 border-2 border-slate-200 rounded-xl
                       text-slate-800 placeholder-slate-400 font-medium
                       focus:outline-none focus:border-sky-500 focus:bg-white transition-all"
            />

            @error('email')
                <p class="text-sm text-red-500 flex items-center gap-1.5 mt-1">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92z"
                                clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-800">
                                Alamat email Anda belum terverifikasi.
                            </p>
                            <button form="send-verification"
                                class="mt-1.5 text-sm font-semibold text-amber-700 hover:text-amber-900 underline underline-offset-2">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Submit -->
        <div class="flex flex-col sm:flex-row items-center gap-4 pt-6 border-t border-sky-100">
            <button
                type="submit"
                id="saveBtn"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5
                       px-8 py-3.5 bg-gradient-to-r from-sky-500 to-sky-600
                       text-white font-semibold rounded-xl shadow-lg
                       hover:shadow-xl focus:ring-2 focus:ring-sky-500 focus:ring-offset-2
                       transition-all disabled:opacity-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>
                </svg>
                <span id="saveBtnText">Simpan Perubahan</span>
            </button>
        </div>
    </form>
</section>
