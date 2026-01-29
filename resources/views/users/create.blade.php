<x-app-layout>
    <!-- Navbar -->
    {{-- Navbar sengaja dimatikan --}}

    <!-- Content -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    👥 Tambah Admin
                </h1>
                <p class="text-slate-500 mt-1">Buat akun admin baru untuk sistem</p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 bg-rose-50 text-rose-700
                            rounded-xl border border-rose-200 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form id="adminForm"
                  method="POST"
                  action="{{ route('users.store') }}"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        👤 Username
                    </label>
                    <input type="text"
                           name="username"
                           placeholder="Masukkan username..."
                           required
                           value="{{ old('username') }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400 focus:outline-none transition">
                    @error('username')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        ✉️ Email
                    </label>
                    <input type="email"
                           name="email"
                           placeholder="contoh@email.com"
                           required
                           value="{{ old('email') }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400 focus:outline-none transition">
                    @error('email')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        🔒 Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Masukkan password minimal 8 karakter..."
                               required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-10
                                      text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                      focus:border-sky-400 focus:outline-none transition">
                        <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-600 transition"
                                onclick="togglePassword('password', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7
                                         -4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        🔒 Konfirmasi Password
                    </label>
                    <div class="relative">
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               placeholder="Ulangi password..."
                               required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-10
                                      text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                      focus:border-sky-400 focus:outline-none transition">
                        <button type="button"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-sky-600 transition"
                                onclick="togglePassword('password_confirmation', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                                         -1.274 4.057-5.064 7-9.542 7
                                         -4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('users.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700
                              border border-slate-300 hover:bg-slate-300 transition
                              font-medium text-sm">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-sky-500 to-sky-600
                                   text-white shadow-md hover:shadow-lg hover:-translate-y-0.5
                                   transition-all font-medium text-sm">
                        ✅ Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('adminForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Saving...';
            });
        });

        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);

            if (field.type === 'password') {
                field.type = 'text';
                button.classList.add('text-sky-600');
            } else {
                field.type = 'password';
                button.classList.remove('text-sky-600');
            }
        }
    </script>
</x-app-layout>
