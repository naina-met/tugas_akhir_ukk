<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Title -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📤 Edit Stok Keluar
                </h1>
                <p class="text-slate-500 mt-1">Perbarui informasi barang keluar</p>
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
            <form id="stockOutForm"
                  action="{{ route('stock-outs.update', $stockOut->id) }}"
                  method="POST"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📅 Tanggal
                    </label>
                    <input type="date"
                           name="date"
                           value="{{ old('date', $stockOut->date->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                </div>

                <!-- Item -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📦 Item
                    </label>
                    <select name="item_id" required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
                        <option value="">-- Pilih Item --</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}"
                                {{ old('item_id', $stockOut->item_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📊 Jumlah
                    </label>
                    <input type="number"
                           name="quantity"
                           value="{{ old('quantity', $stockOut->quantity) }}"
                           min="1"
                           required
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5">
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
                            {{ old('outgoing_destination', $stockOut->outgoing_destination) === 'penjualan' ? 'selected' : '' }}>
                            🛍️ Penjualan
                        </option>

                        <option value="pemakaian_internal"
                            {{ old('outgoing_destination', $stockOut->outgoing_destination) === 'pemakaian_internal' ? 'selected' : '' }}>
                            🔧 Pemakaian Internal
                        </option>

                        <option value="peminjaman"
                            {{ old('outgoing_destination', $stockOut->outgoing_destination) === 'peminjaman' ? 'selected' : '' }}>
                            📦 Peminjaman
                        </option>

                        <option value="rusak"
                            {{ old('outgoing_destination', $stockOut->outgoing_destination) === 'rusak' ? 'selected' : '' }}>
                            💔 Barang Rusak
                        </option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📄 Keterangan
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5">{{ old('description', $stockOut->description) }}</textarea>
                </div>

                <!-- Action -->
                <div class="flex gap-3 justify-end pt-6">
                    <a href="{{ route('stock-outs.index') }}"
                       class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700 border">
                        ❌ Batal
                    </a>
                    <button id="submitBtn"
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-rose-600 text-white">
                        ✅ Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Anti Double Submit -->
    <script>
        document.getElementById('stockOutForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerText = 'Updating...';
        });
    </script>
</x-app-layout>
