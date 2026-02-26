<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📤 Tambah Stok Keluar
                </h1>
                <p class="text-slate-500 mt-1">Catat barang yang keluar dari gudang</p>
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
            <form id="stockOutForm"
                  action="{{ route('stock-outs.store') }}"
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
                                   text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                   focus:border-rose-400 focus:outline-none transition">
                        <option value="">-- Pilih Item --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id') == $item->id ? 'selected' : '' }}>
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
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📊 Jumlah
                    </label>
                    <input type="number"
                           name="quantity"
                           value="{{ old('quantity') }}"
                           required
                           min="1"
                           placeholder="0"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                  focus:border-rose-400 focus:outline-none transition">
                    @error('quantity')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Destination -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📍 Tujuan Keluar
                    </label>
                  <select name="outgoing_destination" required
    class="w-full rounded-lg border border-slate-300 px-4 py-2.5">

    <option value="">-- Pilih Tujuan --</option>

    <option value="penjualan"
        {{ $stockOut->outgoing_destination === 'penjualan' ? 'selected' : '' }}>
        🛍️ Penjualan
    </option>

    <option value="pemakaian_internal"
        {{ $stockOut->outgoing_destination === 'pemakaian_internal' ? 'selected' : '' }}>
        🔧 Pemakaian Internal
    </option>

    <option value="peminjaman"
        {{ $stockOut->outgoing_destination === 'peminjaman' ? 'selected' : '' }}>
        📦 Peminjaman
    </option>

    <option value="rusak"
        {{ $stockOut->outgoing_destination === 'rusak' ? 'selected' : '' }}>
        💔 Barang Rusak
    </option>
</select>

                    @error('outgoing_destination')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📄 Keterangan
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                     text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                     focus:border-rose-400 focus:outline-none transition"
                              placeholder="Masukkan keterangan (opsional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('stock-outs.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700
                              border border-slate-300 hover:bg-slate-300 transition
                              font-medium text-sm">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-rose-500 to-rose-600
                                   text-white shadow-md hover:shadow-lg hover:-translate-y-0.5
                                   transition-all font-medium text-sm">
                        ✅ Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Disable submit -->
    <script>
        document.getElementById('stockOutForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerText = 'Saving...';
        });
    </script>
</x-app-layout>
