<x-app-layout>
    {{-- Navbar memang dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    🏷️ {{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori' }}
                </h1>
                <p class="text-slate-500 mt-1">{{ isset($category) ? 'Perbarui informasi kategori' : 'Tambahkan kategori baru' }}</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">
                <form method="POST"
                      action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}"
                      id="categoryForm"
                      class="space-y-6">
                    @csrf
                    @if (isset($category))
                        @method('PUT')
                    @endif

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📝 Nama Kategori
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $category->name ?? '') }}"
                            placeholder="Masukkan nama kategori..."
                            required
                            autofocus
                            class="w-full px-4 py-2.5
                                   rounded-lg
                                   border border-slate-300
                                   text-slate-700
                                   bg-white
                                   focus:outline-none
                                   focus:ring-2 focus:ring-amber-400
                                   focus:border-amber-400 transition" />
                        @error('name')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📄 Deskripsi
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Masukkan deskripsi kategori (opsional)..."
                            class="w-full px-4 py-2.5
                                   rounded-lg
                                   border border-slate-300
                                   text-slate-700
                                   bg-white
                                   focus:outline-none
                                   focus:ring-2 focus:ring-amber-400
                                   focus:border-amber-400 transition">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 justify-end pt-6">
                        <a href="{{ route('categories.index') }}"
                           class="px-6 py-2.5 rounded-lg
                                  border border-slate-300
                                  bg-slate-200 text-slate-700
                                  hover:bg-slate-300
                                  transition font-medium text-sm">
                            ❌ Batal
                        </a>

                        <button type="submit"
                                id="submitBtn"
                                class="px-6 py-2.5 rounded-lg
                                       bg-gradient-to-r from-amber-500 to-amber-600
                                       text-white
                                       shadow-md hover:shadow-lg hover:-translate-y-0.5
                                       transition-all font-medium text-sm">
                            ✅ {{ isset($category) ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Disable Button Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('categoryForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Saving...';
            });
        });
    </script>
</x-app-layout>
