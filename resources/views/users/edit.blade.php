<x-app-layout>
    <!-- Navbar -->
    {{-- <nav class="bg-transparent mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="text-sm text-slate-600 font-medium">
                {{ Auth::user()->name }}
            </div>
        </div>
    </nav> --}}

    <!-- Content -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">👥 Edit Admin</h1>
                <p class="text-slate-500 mt-1">Perbarui informasi admin akun</p>
            </div>

            <!-- Error Alert -->
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-200 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form id="editUserForm"
                  method="POST"
                  action="{{ route('users.update', $user) }}"
                  class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">👤 Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" required
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">✉️ Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" required
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">🟢 Status</label>
                    <select name="status"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="1" {{ $user->status ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$user->status ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                {{-- ===================== EDIT PASSWORD (KHUSUS SUPERADMIN) ===================== --}}
                @if (strtolower(Auth::user()->role) === 'superadmin')

                <div x-data="{ showPass: false, showConfirm: false }"
                     class="pt-6 border-t border-slate-200">

                    <h2 class="text-lg font-semibold text-slate-800 mb-4">🔐 Ubah Password Admin</h2>

                    <div class="space-y-4">

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input
                                    :type="showPass ? 'text' : 'password'"
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-12
                                           focus:ring-2 focus:ring-sky-400 focus:border-sky-400">

                                <button type="button"
                                        @click="showPass = !showPass"
                                        class="absolute inset-y-0 right-3 flex items-center
                                               text-slate-500 hover:text-slate-700">

                                    <!-- eye -->
                                    <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                 c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7
                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>

                                    <!-- eye-off -->
                                    <svg x-show="showPass" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 3l18 18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M10.58 10.58a3 3 0 004.24 4.24"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                 c1.674 0 3.267.418 4.646 1.146"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M21.542 12C20.268 16.057 16.477 19 12 19
                                                 c-1.674 0-3.267-.418-4.646-1.146"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password (FIXED) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input
                                    :type="showConfirm ? 'text' : 'password'"
                                    name="password_confirmation"
                                    placeholder="Ulangi password baru"
                                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-12
                                           focus:ring-2 focus:ring-sky-400 focus:border-sky-400">

                                <button type="button"
                                        @click="showConfirm = !showConfirm"
                                        class="absolute inset-y-0 right-3 flex items-center
                                               text-slate-500 hover:text-slate-700">

                                    <!-- eye -->
                                    <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                 c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7
                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>

                                    <!-- eye-off (CLEAN & SIMETRIS) -->
                                    <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 3l18 18"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5
                                                 c4.478 0 8.268 2.943 9.542 7
                                                 -1.274 4.057-5.064 7-9.542 7
                                                 -4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                @endif
                {{-- ============================================================================== --}}

                <!-- Buttons -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('users.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700">
                        Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-sky-600 text-white">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Anti Double Submit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('editUserForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Updating...';
            });
        });
    </script>
</x-app-layout>
