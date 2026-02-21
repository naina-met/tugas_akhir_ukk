<x-guest-layout>
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Registrasi Admin</h1>
                <p class="text-slate-500 mt-2">Buat akun admin baru untuk sistem</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">
                <!-- Form -->
                <form method="POST" action="{{ route('admin.register') }}" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                            👤 Username
                        </label>
                        <input id="username" type="text" name="username" 
                               value="{{ old('username') }}" 
                               required autofocus autocomplete="username"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition
                                      @error('username') border-rose-500 focus:ring-rose-400 @enderror" />
                        @error('username')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            ✉️ Email
                        </label>
                        <input id="email" type="email" name="email" 
                               value="{{ old('email') }}" 
                               required autocomplete="email"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition
                                      @error('email') border-rose-500 focus:ring-rose-400 @enderror" />
                        @error('email')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            🔐 Password
                        </label>
                        <input id="password" type="password" name="password" 
                               required autocomplete="new-password"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition
                                      @error('password') border-rose-500 focus:ring-rose-400 @enderror" />
                        @error('password')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                            🔐 Konfirmasi Password
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" 
                               required autocomplete="new-password"
                               class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition" />
                    </div>

                    <!-- Info -->
                    <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                        <p class="text-sm text-sky-700">
                            ℹ️ <strong>Akun Anda akan pending</strong> menunggu persetujuan dari superadmin sebelum bisa login.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-sky-500 to-sky-600 text-white font-medium py-2.5 rounded-lg
                                                hover:shadow-lg hover:-translate-y-0.5 transition-all">
                        ✨ Daftar Admin
                    </button>
                </form>

                <!-- Link ke Login -->
                <div class="text-center mt-6">
                    <p class="text-slate-600 text-sm">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-sky-600 font-semibold hover:text-sky-700">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
