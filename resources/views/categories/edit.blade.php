<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    🏷️ Edit Kategori
                </h1>
                <p class="text-slate-500 mt-1">Perbarui informasi kategori</p>
            </div>

            <!-- Form Card -->
            <form id="editCategoryForm"
                  action="{{ route('categories.update', $category) }}"
                  method="POST"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📝 Nama Kategori
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ $category->name }}"
                           placeholder="Masukkan nama kategori..."
                           required autofocus
                           class="w-full rounded-lg
                                  border border-slate-300
                                  px-4 py-2.5
                                  text-slate-700 bg-white
                                  focus:ring-2 focus:ring-amber-400
                                  focus:border-amber-400
                                  focus:outline-none transition" />
                    @error('name')
                        <p class="text-rose-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📄 Deskripsi
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Masukkan deskripsi kategori (opsional)..."
                              class="w-full rounded-lg
                                     border border-slate-300
                                     px-4 py-2.5
                                     text-slate-700 bg-white
                                     focus:ring-2 focus:ring-amber-400
                                     focus:border-amber-400
                                     focus:outline-none transition">{{ $category->description }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">

                    <a href="{{ route('categories.index') }}"
                       class="px-6 py-2.5 rounded-lg
                              border border-slate-300
                              bg-slate-200 text-slate-700
                              hover:bg-slate-300
                              transition font-medium text-sm">
                        ❌ Batal
                    </a>

                    <button id="submitBtn"
                            type="submit"
                            class="bg-gradient-to-r from-amber-500 to-amber-600
                                   text-white px-6 py-2.5 rounded-lg
                                   shadow-md
                                   hover:shadow-lg hover:-translate-y-0.5
                                   transition-all
                                   font-medium text-sm">
                        ✅ Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Disable Submit Button Script -->
    <script>
        document.getElementById('editCategoryForm').addEventListener('submit', function () {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Updating...';
        });
    </script>
</x-app-layout>
