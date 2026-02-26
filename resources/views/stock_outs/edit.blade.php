<x-app-layout>
    {{-- Navbar sengaja dimatikan --}}

    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <!-- Check if borrowed item is already returned -->
          @if($stockOut->is_borrowed && is_null($stockOut->returned_at))
    <div class="mb-8 px-6 py-5 bg-blue-50 text-blue-700 rounded-xl border-2 border-blue-200 shadow-md">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold">⚠️ Barang Sedang Dipinjam</p>
                <p class="text-sm mt-1">Data tidak dapat diubah karena barang ini masih dalam status dipinjam. Selesaikan transaksi peminjaman (kembalikan barang) terlebih dahulu.</p>
            </div>
        </div>
        <a href="{{ route('stock-outs.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
            ← Kembali ke Daftar
        </a>
    </div>

@elseif($stockOut->is_borrowed && $stockOut->returned_at)
    <div class="mb-8 px-6 py-5 bg-amber-50 text-amber-700 rounded-xl border-2 border-amber-200 shadow-md">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold">⚠️ Barang Sudah Dikembalikan</p>
                <p class="text-sm mt-1">Transaksi peminjaman ini tidak dapat diubah karena barang sudah dikembalikan pada {{ $stockOut->returned_at->format('d M Y H:i') }}.</p>
            </div>
        </div>
        <a href="{{ route('stock-outs.index') }}" class="mt-4 inline-block px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition text-sm font-medium">
            ← Kembali ke Daftar
        </a>
    </div>

@else
    {{-- ```

Dengan perubahan di atas, kalau ada orang iseng yang mengetik URL edit secara manual (misal: `/stock-outs/5/edit`), mereka tetap akan diblokir oleh sistem dan hanya melihat kotak pesan peringatan biru.

---

### Langkah Selanjutnya

Untuk membuat **tombol di tabel halamannya** menjadi non-aktif (agar tidak bisa di-klik sama sekali dari awal), kita perlu mengedit file tempat tabel itu berada (biasanya bernama `index.blade.php`).

Apakah kamu ingin saya bantu menyesuaikan file `index.blade.php` agar tombol "Edit" dan "Hapus" otomatis *disabled* (berwarna abu-abu) saat barangnya sedang dipinjam? Jika iya, silakan kirimkan kode tabel dari file tersebut ya! --}}

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
                  action="{{ route('stock-outs.update', $stockOut) }}"
                  method="POST"
                  class="bg-white rounded-2xl shadow-xl
                         border border-slate-200
                         p-8 space-y-6">
                @csrf
                @method('PUT')

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
                                {{ $item->id == $stockOut->item_id ? 'selected' : '' }}>
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
                           value="{{ $stockOut->quantity }}"
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
                    <select name="outgoing_destination"
                            id="destinationSelect"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                   text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                   focus:border-rose-400 focus:outline-none transition">
                        <option value="">-- Pilih Tujuan --</option>
                        <option value="penjualan"
                            {{ $stockOut->outgoing_destination == 'penjualan' ? 'selected' : '' }}>
                            🛍️ Penjualan
                        </option>
                        <option value="pemakaian_internal"
                            {{ $stockOut->outgoing_destination == 'pemakaian_internal' ? 'selected' : '' }}>
                            🔧 Pemakaian Internal
                        </option>
                        <option value="peminjaman"
                            {{ $stockOut->outgoing_destination == 'peminjaman' ? 'selected' : '' }}>
                            📦 Peminjaman
                        </option>
                        <option value="rusak"
                            {{ $stockOut->outgoing_destination == 'rusak' ? 'selected' : '' }}>
                            💔 Barang Rusak
                        </option>
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

                <!-- Conditional borrowing fields -->
                <div id="borrowingFields" class="hidden">
                    <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                        📅 Tanggal Pengembalian yang Diharapkan
                    </label>
                    <input type="date"
                           name="return_date"
                           value="{{ old('return_date', $stockOut->return_date) }}"
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
                              placeholder="Masukkan keterangan (opsional)..."
                              class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                     text-slate-700 bg-white focus:ring-2 focus:ring-rose-400
                                     focus:border-rose-400 focus:outline-none transition">{{ $stockOut->description }}</textarea>
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
                        ✅ Perbarui
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <!-- Anti Double Submit & Toggle Borrowing Fields -->
    <script>
        // Show/hide borrowing fields based on destination
        const destinationSelect = document.getElementById('destinationSelect');
        const itemSelect = document.querySelector('select[name="item_id"]');
        const borrowingFields = document.getElementById('borrowingFields');
        const borrowingInfo = document.getElementById('borrowingInfo');
        const borrowCountInfo = document.getElementById('borrowCountInfo');
        const returnDateInput = document.querySelector('input[name="return_date"]');
        const currentItemId = {{ $stockOut->item_id }};

        async function checkBorrowCount() {
            if (destinationSelect.value === 'peminjaman' && itemSelect.value) {
                try {
                    const response = await fetch(`/api/stock-outs/borrow-count/${itemSelect.value}`);
                    const data = await response.json();
                    
                    // For edit form, we need to exclude the current record from consideration
                    let displayCount = data.currentCount;
                    let displayCanBorrow = data.canBorrow;
                    
                    // If we're editing the same item and it's already borrowed, don't count it against the limit
                    if (itemSelect.value == currentItemId && {{ $stockOut->is_borrowed ? 'true' : 'false' }}) {
                        displayCount = Math.max(0, data.currentCount - 1);
                        displayCanBorrow = displayCount < 2;
                    }
                    
                    if (displayCanBorrow) {
                        borrowCountInfo.innerHTML = `✅ Saat ini: <strong>${displayCount}</strong> barang dipinjam (dapat meminjam ${2 - displayCount} lagi)`;
                        borrowingInfo.classList.remove('hidden');
                    } else {
                        borrowCountInfo.innerHTML = `❌ Limit tercapai: <strong>${displayCount}</strong>/2 barang sudah dipinjam. Tunggu hingga salah satu dikembalikan.`;
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
                checkBorrowCount();
            } else {
                borrowingFields.classList.add('hidden');
                borrowingInfo.classList.add('hidden');
                returnDateInput.required = false;
                returnDateInput.value = '';
            }
        }

        destinationSelect.addEventListener('change', toggleBorrowingFields);
        itemSelect.addEventListener('change', checkBorrowCount);
        
        // Check on page load
        toggleBorrowingFields();

        document.getElementById('stockOutForm')
            .addEventListener('submit', function () {
                const btn = document.getElementById('submitBtn');
                btn.disabled = true;
                btn.innerText = 'Updating...';
            });
    </script>
</x-app-layout>