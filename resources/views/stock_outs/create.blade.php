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
                    <select id="destinationSelect"
                            name="outgoing_destination"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                   focus:border-rose-400 focus:outline-none transition">
                        <option value="">-- Pilih Tujuan --</option>
                        <option value="penjualan" {{ old('outgoing_destination') == 'penjualan' ? 'selected' : '' }}>🛍️ Penjualan</option>
                        <option value="pemakaian_internal" {{ old('outgoing_destination') == 'pemakaian_internal' ? 'selected' : '' }}>🔧 Pemakaian Internal</option>
                        <option value="peminjaman" {{ old('outgoing_destination') == 'peminjaman' ? 'selected' : '' }}>📦 Peminjaman</option>
                        <option value="rusak" {{ old('outgoing_destination') == 'rusak' ? 'selected' : '' }}>💔 Barang Rusak</option>
                    </select>
                    @error('outgoing_destination')
                        <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Borrowing Limit Info -->
                <div id="borrowingInfo" class="hidden p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <span class="font-semibold">ℹ️ Batas Peminjaman:</span><br>
                        Maksimal 2 barang yang sama dapat dipinjam per hari. 
                        <span id="borrowCountInfo" class="font-medium"></span>
                    </p>
                </div>

                <!-- Expected Return Date (Show only for Peminjaman) -->
                <div id="borrowingFields" class="hidden">
                    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
            👤 Nama Peminjam
        </label>
        <input type="text"
               name="borrower_name"
               id="borrower_name"
               value="{{ old('borrower_name') }}"
               placeholder="Masukkan nama peminjam..."
               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-700 bg-white focus:ring-2 focus:ring-rose-400 focus:border-rose-400 focus:outline-none transition">
        @error('borrower_name')
            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📅 Tanggal Pengembalian yang Diharapkan
                    </label>
                    <input type="date"
                           name="return_date"
                           value="{{ old('return_date') }}"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                  text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                  focus:border-rose-400 focus:outline-none transition">
                    @error('return_date')
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

    <!-- Scripts -->
    <script>
        // Show/hide borrowing fields based on destination
        const destinationSelect = document.getElementById('destinationSelect');
        const itemSelect = document.querySelector('select[name="item_id"]');
        const borrowingFields = document.getElementById('borrowingFields');
        const borrowingInfo = document.getElementById('borrowingInfo');
        const borrowCountInfo = document.getElementById('borrowCountInfo');
        const returnDateInput = document.querySelector('input[name="return_date"]');
        const borrowerNameInput = document.getElementById('borrower_name');

        async function checkBorrowCount() {
            if (destinationSelect.value === 'peminjaman' && itemSelect.value) {
                try {
                    const response = await fetch(`/api/stock-outs/borrow-count/${itemSelect.value}`);
                    const data = await response.json();
                    
                    if (data.canBorrow) {
                        borrowCountInfo.innerHTML = `✅ Saat ini: <strong>${data.currentCount}</strong> barang dipinjam (dapat meminjam ${data.remaining} lagi)`;
                        borrowingInfo.classList.remove('hidden');
                    } else {
                        borrowCountInfo.innerHTML = `❌ Limit tercapai: <strong>${data.currentCount}</strong>/2 barang sudah dipinjam. Tunggu hingga salah satu dikembalikan.`;
                        borrowingInfo.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Error checking borrow count:', error);
                    borrowingInfo.classList.add('hidden');
                }
            } else {
                borrowingInfo.classList.add('hidden');
            }
        }

function toggleBorrowingFields() {
    if (destinationSelect.value === 'peminjaman') {
        borrowingFields.classList.remove('hidden');
        returnDateInput.required = true;
        borrowerNameInput.required = true; // ✨ TAMBAHAN
        checkBorrowCount();
    } else {
        borrowingFields.classList.add('hidden');
        borrowingInfo.classList.add('hidden');
        returnDateInput.required = false;
        borrowerNameInput.required = false; // ✨ TAMBAHAN
        returnDateInput.value = '';
        borrowerNameInput.value = '';       // ✨ TAMBAHAN
    }
}

        destinationSelect.addEventListener('change', toggleBorrowingFields);
        itemSelect.addEventListener('change', checkBorrowCount);
        
        // Check on page load
        toggleBorrowingFields();

        document.getElementById('stockOutForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerText = 'Saving...';
        });
    </script>
</x-app-layout>