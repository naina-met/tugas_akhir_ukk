<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📥 Edit Stok Masuk
                </h1>
                <p class="text-slate-500 mt-1">Perbarui informasi barang masuk</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 px-5 py-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-200 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="stockInForm"
                  action="{{ route('stock-ins.update', $stockIn) }}"
                  method="POST"
                  class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Item -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">📦 Item</label>
                    <select name="item_id" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-emerald-400
                                   focus:border-emerald-400 focus:outline-none transition">
                        <option value="">-- Pilih Item --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ $item->id == $stockIn->item_id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">📊 Jumlah</label>
                    <input type="number" name="quantity" value="{{ $stockIn->quantity }}" required
                           placeholder="0" min="1"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-emerald-400
                                  focus:border-emerald-400 focus:outline-none transition">
                    @error('quantity')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $isOther = !in_array($stockIn->incoming_source, ['Pembelian', 'Pengembalian']);
                @endphp

                <!-- Source -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">📌 Sumber Barang</label>

                    <select id="incoming_source_select"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-emerald-400
                                   focus:border-emerald-400 focus:outline-none transition">
                        <option value="Pembelian" {{ $stockIn->incoming_source == 'Pembelian' ? 'selected' : '' }}>
                            🛒 Pembelian
                        </option>
                        {{-- <option value="Pengembalian" {{ $stockIn->incoming_source == 'Pengembalian' ? 'selected' : '' }}>
                            ↩️ Pengembalian
                        </option> --}}
                        <option value="Lainnya" {{ $isOther ? 'selected' : '' }}>
                            📝 Lainnya
                        </option>
                    </select>

                    <!-- INI YANG DIKIRIM KE BACKEND -->
                    <input type="hidden"
                           name="incoming_source"
                           id="incoming_source_hidden"
                           value="{{ $stockIn->incoming_source }}"
                           required>
                    @error('incoming_source')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Source Lainnya -->
                <div id="otherSourceWrapper" class="{{ $isOther ? '' : 'hidden' }}">
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">📝 Sumber Lainnya</label>
                    <input type="text"
                           id="incoming_source_other"
                           value="{{ $isOther ? $stockIn->incoming_source : '' }}"
                           placeholder="Contoh: Donasi, Gudang Cabang A"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-emerald-400
                                  focus:border-emerald-400 focus:outline-none transition">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">📄 Keterangan</label>
                    <textarea name="description" rows="4"
                              placeholder="Masukkan keterangan (opsional)..."
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                     text-slate-700 bg-white focus:ring-2 focus:ring-emerald-400
                                     focus:border-emerald-400 focus:outline-none transition">{{ $stockIn->description }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('stock-ins.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700
                              border border-slate-300 hover:bg-slate-300 transition
                              font-medium text-sm">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600
                                   text-white shadow-md hover:shadow-lg hover:-translate-y-0.5
                                   transition-all font-medium text-sm">
                        ✅ Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const select = document.getElementById('incoming_source_select');
        const otherWrap = document.getElementById('otherSourceWrapper');
        const otherInput = document.getElementById('incoming_source_other');
        const hiddenInput = document.getElementById('incoming_source_hidden');
        const submitBtn = document.getElementById('submitBtn');

        select.addEventListener('change', function () {
            if (this.value === 'Lainnya') {
                otherWrap.classList.remove('hidden');
                hiddenInput.value = '';
            } else {
                otherWrap.classList.add('hidden');
                otherInput.value = '';
                hiddenInput.value = this.value;
            }
        });

        otherInput.addEventListener('input', function () {
            hiddenInput.value = this.value.trim();
        });

        document.getElementById('stockInForm').addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.innerText = 'Updating...';
        });
    </script>
</x-app-layout>
