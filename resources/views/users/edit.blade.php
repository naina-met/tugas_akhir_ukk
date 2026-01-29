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
                <h1 class="text-3xl font-bold text-slate-800">
                    👥 Edit Admin
                </h1>
                <p class="text-slate-500 mt-1">Perbarui informasi admin akun</p>
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
            <form id="editUserForm"
                  method="POST"
                  action="{{ route('users.update', $user) }}"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        👤 Username
                    </label>
                    <input type="text"
                           name="username"
                           value="{{ $user->username }}"
                           placeholder="Masukkan username..."
                           required
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
                           value="{{ $user->email }}"
                           placeholder="contoh@email.com"
                           required
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400 focus:outline-none transition">
                    @error('email')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        🟢 Status
                    </label>
                    <select name="status"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                   focus:border-sky-400 focus:outline-none transition">
                        <option value="1" {{ $user->status ? 'selected' : '' }}>
                            ✅ Aktif
                        </option>
                        <option value="0" {{ !$user->status ? 'selected' : '' }}>
                            ⛔ Tidak Aktif
                        </option>
                    </select>
                    @error('status')
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
                        ✅ Perbarui
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
