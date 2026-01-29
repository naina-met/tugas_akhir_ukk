<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📦 {{ isset($item) ? 'Ubah Barang' : 'Tambah Barang' }}
                </h1>
                <p class="text-slate-500 mt-1">{{ isset($item) ? 'Perbarui informasi item' : 'Tambahkan item baru ke sistem' }}</p>
            </div>

            <!-- Form Card (FORM = CARD, sama seperti contoh) -->
            <form id="itemForm"
                  method="POST"
                  action="{{ isset($item) ? route('items.update', $item) : route('items.store') }}"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf
                @isset($item)
                    @method('PUT')
                @endisset

                <!-- Name -->
                <div>
                    <label for="name"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📝 Nama Item
                    </label>
                    <input id="name"
                           name="name"
                           type="text"
                           value="{{ old('name', $item->name ?? '') }}"
                           placeholder="Masukkan nama item..."
                           required autofocus
                           class="w-full rounded-lg
                                  border border-slate-300
                                  px-4 py-2.5
                                  text-slate-700
                                  bg-white
                                  focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400
                                  focus:outline-none transition" />
                    @error('name')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        🔖 Kode Item
                    </label>
                    <input id="code"
                           name="code"
                           type="text"
                           value="{{ old('code', $item->code ?? '') }}"
                           placeholder="Masukkan kode item..."
                           required
                           class="w-full rounded-lg
                                  border border-slate-300
                                  px-4 py-2.5
                                  text-slate-700
                                  bg-white
                                  focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400
                                  focus:outline-none transition" />
                    @error('code')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        🏷️ Kategori
                    </label>
                    <select id="category_id"
                            name="category_id"
                            required
                            class="w-full rounded-lg
                                   border border-slate-300
                                   px-4 py-2.5
                                   text-slate-700
                                   bg-white
                                   focus:ring-2 focus:ring-sky-400
                                   focus:border-sky-400
                                   focus:outline-none transition">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $item->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
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
                              placeholder="Masukkan deskripsi item..."
                              class="w-full rounded-lg
                                     border border-slate-300
                                     px-4 py-2.5
                                     text-slate-700
                                     bg-white
                                     focus:ring-2 focus:ring-sky-400
                                     focus:border-sky-400
                                     focus:outline-none transition">{{ old('description', $item->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit"
                           class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📐 Satuan
                    </label>
                    <select id="unit"
                            name="unit"
                            class="w-full rounded-lg
                                   border border-slate-300
                                   px-4 py-2.5
                                   text-slate-700
                                   bg-white
                                   focus:ring-2 focus:ring-sky-400
                                   focus:border-sky-400
                                   focus:outline-none transition">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach (['pcs','box','kg','liter'] as $u)
                            <option value="{{ $u }}"
                                {{ old('unit', $item->unit ?? '') == $u ? 'selected' : '' }}>
                                {{ ucfirst($u) }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('items.index') }}"
                       class="px-6 py-2.5 rounded-lg
                              bg-slate-200 text-slate-700
                              border border-slate-300
                              hover:bg-slate-300 transition
                              font-medium text-sm">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg
                                   bg-gradient-to-r from-sky-500 to-sky-600
                                   text-white
                                   shadow-md hover:shadow-lg hover:-translate-y-0.5
                                   transition-all
                                   font-medium text-sm">
                        ✅ {{ isset($item) ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Disable Submit Button Script -->
    <script>
        document.getElementById('itemForm').addEventListener('submit', function () {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerText = '{{ isset($item) ? "Updating..." : "Saving..." }}';
        });
    </script>
</x-app-layout>
