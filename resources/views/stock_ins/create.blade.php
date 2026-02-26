<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📥 Tambah Stok Masuk
                </h1>
                <p class="text-slate-500 mt-1">Catat barang yang masuk ke gudang</p>
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

            <!-- Form Card -->
            <form id="stockInForm"
                  action="{{ route('stock-ins.store') }}"
                  method="POST"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf

                <!-- Item -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📦 Item
                    </label>
                    <select name="item_id"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                   focus:border-sky-400 focus:outline-none transition">
                        <option value="">-- Pilih Item --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📊 Jumlah
                    </label>
                    <input type="number"
                           name="quantity"
                           required
                           min="1"
                           value="{{ old('quantity', 1) }}"
                           placeholder="0"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400 focus:outline-none transition">
                    @error('quantity')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📌 Sumber Barang
                    </label>

                    <select id="incoming_source_select"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                   focus:border-sky-400 focus:outline-none transition">
                        <option value="">-- Pilih Sumber --</option>
                        <option value="Pembelian" {{ old('incoming_source') == 'Pembelian' ? 'selected' : '' }}>🛒 Pembelian</option>
                        {{-- <option value="Pengembalian" {{ old('incoming_source') == 'Pengembalian' ? 'selected' : '' }}>↩️ Pengembalian</option> --}}
                        <option value="Lainnya" {{ old('incoming_source') == 'Lainnya' || strpos(old('incoming_source', ''), 'Lainnya') === 0 ? 'selected' : '' }}>📝 Lainnya</option>
                    </select>

                    <!-- VALUE YANG DIKIRIM KE BACKEND -->
                    <input type="hidden"
                           name="incoming_source"
                           id="incoming_source_hidden"
                           value="{{ old('incoming_source') }}"
                           required>
                    @error('incoming_source')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source Lainnya -->
                <div id="otherSourceWrapper" class="hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📝 Sumber Lainnya
                    </label>
                    <input type="text"
                           id="incoming_source_other"
                           value="{{ strpos(old('incoming_source', ''), 'Lainnya') === 0 && strlen(old('incoming_source')) > 0 ? substr(old('incoming_source'), 8) : '' }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                  focus:border-sky-400 focus:outline-none transition"
                           placeholder="Contoh: Donasi, Gudang Cabang A">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📄 Keterangan
                    </label>
                    <textarea name="description"
                              rows="4"
                              placeholder="Masukkan keterangan (opsional)..."
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                     text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                     focus:border-sky-400 focus:outline-none transition">{{ old('description', '') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('stock-ins.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700
                              border border-slate-300 hover:bg-slate-300
                              transition font-medium text-sm">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600
                                   text-white shadow-md hover:shadow-lg hover:-translate-y-0.5
                                   transition-all font-medium text-sm">
                        ✅ Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        const sourceSelect = document.getElementById('incoming_source_select');
        const otherWrapper = document.getElementById('otherSourceWrapper');
        const otherInput = document.getElementById('incoming_source_other');
        const hiddenSource = document.getElementById('incoming_source_hidden');
        const submitBtn = document.getElementById('submitBtn');

        sourceSelect.addEventListener('change', function () {
            if (this.value === 'Lainnya') {
                otherWrapper.classList.remove('hidden');
                otherInput.required = true;
                hiddenSource.value = '';
            } else {
                otherWrapper.classList.add('hidden');
                otherInput.required = false;
                otherInput.value = '';
                hiddenSource.value = this.value;
            }
        });

        otherInput.addEventListener('input', function () {
            hiddenSource.value = this.value.trim();
        });

        document.getElementById('stockInForm').addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Saving...';
        });
    </script>
</x-app-layout>
