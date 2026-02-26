<x-app-layout>
    {{-- Navbar dimatikan --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50">
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                <!-- Header Section -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800">📤 Stok Keluar</h1>
                            <p class="text-slate-500 mt-1">Kelola barang keluar dari gudang</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Search Icon Button -->
                            <button onclick="toggleSearchStockOut()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-rose-300 hover:bg-rose-50 transition-all shadow-sm">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>

                            <!-- Search Form (Hidden) -->
                            <form id="searchFormStockOut" action="{{ route('stock-outs.index') }}" method="GET" class="hidden">
                                <div class="relative w-72">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInputStockOut"
                                           placeholder="Cari nama item atau tujuan..." 
                                           value="{{ request('search') }}"
                                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-10 bg-white shadow-lg
                                                  focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-rose-400 transition">
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </form>

                            @if(strtolower(Auth::user()->role) === 'superadmin')
                            <button onclick="toggleHistoryModalStockOut()" class="flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2.5 rounded-xl border border-indigo-200 hover:bg-indigo-200 transition font-medium shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Histori
                            </button>
                            @endif

                            @if(strtolower(Auth::user()->role) === 'admin')
                            <a href="{{ route('stock-outs.create') }}"
                               class="bg-gradient-to-r from-rose-500 to-rose-600 text-white px-6 py-2 rounded-xl
                                      shadow-md hover:shadow-lg hover:-translate-y-0.5
                                      transition-all font-medium">
                                + Tambah Stok Keluar
                            </a>
                            @endif

                            <a href="{{ route('export.stockouts') }}"
                               class="bg-emerald-100 text-emerald-700 px-6 py-2 rounded-xl border border-emerald-200
                                      hover:bg-emerald-200 transition font-medium shadow-sm"
                               onclick="return confirmExport();">
                                📊 Export
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 px-5 py-4 bg-gradient-to-r from-emerald-50 to-emerald-50 text-emerald-700
                                rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                <div class="overflow-x-auto">
                <table class="w-full min-w-max text-left border-collapse">

                    <!-- HEADER -->
                    <thead class="bg-gradient-to-r from-rose-50 to-rose-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">#</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Jumlah</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Tujuan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Peminjam</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Pengembalian</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Keterangan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Pengguna</th>
                            @if(strtolower(Auth::user()->role) === 'admin')
                            <th class="px-6 py-4 text-sm font-semibold text-center text-slate-700">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="text-slate-700">
                        @forelse ($stockOuts as $index => $stockOut)
                            <tr class="hover:bg-rose-50 transition-colors border-b border-slate-100">
                                <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $stockOut->item->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                        -{{ $stockOut->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
    @if($stockOut->outgoing_destination === 'rusak')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
            💔 Rusak
        </span>
    @elseif($stockOut->outgoing_destination === 'penjualan')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
            🛍️ Penjualan
        </span>
    @elseif($stockOut->outgoing_destination === 'peminjaman')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
            🤝 Peminjaman
        </span>
    @elseif($stockOut->outgoing_destination === 'pemakaian_internal')
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
            🏢 Internal
        </span>
    @else
        {{-- Fallback untuk menampilkan data asli jika tidak ada yang cocok --}}
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
            {{ ucwords(str_replace('_', ' ', $stockOut->outgoing_destination)) }}
        </span>
    @endif
</td>
<td class="px-6 py-4 text-sm font-medium text-slate-700">
    {{ $stockOut->borrower_name ?? '-' }}
</td>

                                <!-- Return Status Column -->
                                <td class="px-6 py-4 text-sm">
                                    @if($stockOut->is_borrowed)
                                        @if($stockOut->returned_at)
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                                    ✅ Dikembalikan
                                                </span>
                                                <span class="text-xs text-slate-500">{{ $stockOut->returned_at->format('d M Y H:i') }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                                    ⏳ Menunggu
                                                </span>
                                                <span class="text-xs text-slate-500">Kembali: {{ \Carbon\Carbon::parse($stockOut->return_date)->format('d M Y') }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->user->username }}</td>
                                @if(strtolower(Auth::user()->role) === 'admin')
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        @if($stockOut->is_borrowed && !$stockOut->returned_at)
                                        <button onclick="confirmMarkReturned({{ $stockOut->id }}, '{{ $stockOut->item->name }}')"
                                                class="px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200
                                                       border border-emerald-200 rounded-lg text-xs font-medium transition">
                                            ✅ Kembalikan
                                        </button>
                                        @endif
                                       <div class="flex gap-2">
    {{-- 1. LOGIKA TOMBOL EDIT --}}
    @if($stockOut->is_borrowed)
        {{-- Jika ini peminjaman (baik sudah kembali atau belum), Edit MATI --}}
        <button disabled 
                class="px-3 py-1.5 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200 rounded-lg text-xs font-medium"
                title="Transaksi peminjaman tidak dapat diubah">
            ✏️ Edit
        </button>
    @else
        {{-- Jika bukan peminjaman, Edit NYALA --}}
        <a href="{{ route('stock-outs.edit', $stockOut) }}" 
           class="px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 border border-amber-200 rounded-lg text-xs font-medium transition">
            ✏️ Edit
        </a>
    @endif


    {{-- 2. LOGIKA TOMBOL HAPUS --}}
    @if($stockOut->is_borrowed)
        {{-- Jika ini peminjaman (baik sudah kembali atau belum), Hapus MATI --}}
        <button disabled
                class="px-3 py-1.5 bg-slate-100 text-slate-400 cursor-not-allowed border border-slate-200 rounded-lg text-xs font-medium"
                title="Transaksi peminjaman tidak dapat dihapus">
            🗑️ Hapus
        </button>
    @else
        {{-- Jika bukan peminjaman, Hapus NYALA --}}
        <form action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="button"
                    onclick="confirmDelete(event, '{{ $stockOut->item->name }}', 'barang keluar')"
                    class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 border border-rose-200 rounded-lg text-xs font-medium transition">
                🗑️ Hapus
            </button>
        </form>
    @endif


    {{-- 3. LOGIKA TOMBOL KEMBALIKAN --}}
    @if($stockOut->is_borrowed && is_null($stockOut->returned_at))
        {{-- Tombol ini CUMA muncul kalau sedang dipinjam DAN belum kembali --}}
        {{-- <button onclick="handleReturn({{ $stockOut->id }})"
                class="px-3 py-1.5 bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm rounded-lg text-xs font-medium transition">
            ✅ Kembalikan
        </button> --}}
    @endif
</div>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ strtolower(Auth::user()->role) === 'admin' ? '9' : '8' }}" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-slate-400 font-medium">No stock out records found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                    <!-- Pagination -->
                    @if ($stockOuts->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-center">
                            {{ $stockOuts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(strtolower(Auth::user()->role) === 'superadmin')
    <div id="historyModalStockOut" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="toggleHistoryModalStockOut()"></div>
            <div class="relative inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Histori Aktivitas - Stok Keluar
                    </h3>
                    <button onclick="toggleHistoryModalStockOut()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-slate-700">Waktu</th>
                                <th class="px-4 py-3 font-semibold text-slate-700">Pengguna</th>
                                <th class="px-4 py-3 font-semibold text-slate-700">Aksi</th>
                                <th class="px-4 py-3 font-semibold text-slate-700">Nama Barang</th>
                                <th class="px-4 py-3 font-semibold text-slate-700">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($activityLogs as $log)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-slate-600">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3"><span class="font-medium text-slate-700">{{ $log->user->username ?? '-' }}</span></td>
                                    <td class="px-4 py-3">
                                        @if($log->action === 'Tambah')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">Tambah</span>
                                        @elseif($log->action === 'Edit')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-600">Edit</span>
                                        @elseif($log->action === 'Hapus')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 text-rose-600">Hapus</span>
                                        @elseif($log->action === 'Kembalikan')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600">✅ Kembalikan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $log->item_name }}</td>
                                    <td class="px-4 py-3">
                                        <button type="button" onclick='showDetailStockOut(@json($log->details), "{{ $log->action }}")' class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 rounded-lg text-xs font-medium transition-all">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400">Belum ada histori aktivitas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Confirm Export Script -->
    <script>
        function confirmExport() {
            return confirm('Apakah Anda yakin mau mengekspor dalam bentuk Excel?');
        }

        function toggleSearchStockOut() {
            const form = document.getElementById('searchFormStockOut');
            const input = document.getElementById('searchInputStockOut');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                input.focus();
            }
        }

        function toggleHistoryModalStockOut() {
            const modal = document.getElementById('historyModalStockOut');
            if (modal) modal.classList.toggle('hidden');
        }

        function showDetailStockOut(details, action) {
            let detailsObj = {};
            try {
                detailsObj = typeof details === 'string' ? JSON.parse(details) : details;
            } catch (e) {
                detailsObj = details;
            }

            let html = '<div class="text-left text-sm space-y-2">';
            let titleIcon = '📋';
            let titleText = 'Detail';

            // TAMBAH ACTION
            if (action === 'Tambah') {
                titleIcon = '➕';
                titleText = 'Data Stok Keluar yang Ditambahkan';
                
                // Cari baris ini di dalam function showDetailStockOut
const fieldsToShow = ['date', 'item_name', 'quantity', 'outgoing_destination', 'borrower_name', 'description', 'is_borrowed', 'return_date'];
const fieldNames = {
    'date': 'Tanggal',
    'item_name': 'Nama Barang',
    'quantity': 'Jumlah',
    'outgoing_destination': 'Tujuan',
    'borrower_name': 'Nama Peminjam', // ✨ Tambahkan baris ini
    'description': 'Keterangan',
    'is_borrowed': 'Status Peminjaman',
    'return_date': 'Tanggal Pengembalian'
};

                for (const field of fieldsToShow) {
                    if (detailsObj[field] !== undefined) {
                        let value = detailsObj[field] || '-';
                        
                        // Format is_borrowed as yes/no
                        if (field === 'is_borrowed') {
                            value = value === 1 || value === true ? '✅ Ya (Dipinjam)' : '❌ Tidak';
                        }
                        
                        // Skip description if it's null or empty
                        if (field === 'description' && (!value || value === '-')) {
                            continue;
                        }
                        
                        let bgColor = 'bg-emerald-50 border-emerald-400 text-emerald-700';
                        
                        // Highlight borrowed and return_date fields
                        if (field === 'is_borrowed' || field === 'return_date') {
                            if (detailsObj['is_borrowed'] === 1 || detailsObj['is_borrowed'] === true) {
                                bgColor = 'bg-purple-50 border-purple-400 text-purple-700';
                            }
                        }
                        
                        html += '<div class="' + bgColor + ' p-3 rounded-lg border-l-4">';
                        html += '<span class="font-semibold">' + fieldNames[field] + ':</span><br>';
                        html += '<span class="text-slate-700">' + value + '</span>';
                        html += '</div>';
                    }
                }
            }
            // EDIT ACTION
            else if (action === 'Edit') {
                titleIcon = '✏️';
                titleText = 'Perubahan Stok Keluar';
                
                if (detailsObj.changes && typeof detailsObj.changes === 'object') {
                    const changes = detailsObj.changes;
                    const fieldsToShow = ['quantity', 'outgoing_destination', 'description', 'is_borrowed', 'return_date'];
                    
                    let hasChanges = false;
                    for (const field of fieldsToShow) {
                        if (changes[field] !== undefined) {
                            hasChanges = true;
                            const displayName = {
                                'quantity': 'Jumlah',
                                'outgoing_destination': 'Tujuan',
                                'description': 'Keterangan',
                                'is_borrowed': 'Status Peminjaman',
                                'return_date': 'Tanggal Pengembalian'
                            }[field] || field;
                            
                            const changeData = changes[field];
                            let fromVal = (changeData.from || '-') === null ? '-' : changeData.from;
                            let toVal = (changeData.to || '-') === null ? '-' : changeData.to;
                            
                            // Format boolean values
                            if (field === 'is_borrowed') {
                                fromVal = fromVal === 1 || fromVal === true ? '✅ Ya (Dipinjam)' : '❌ Tidak';
                                toVal = toVal === 1 || toVal === true ? '✅ Ya (Dipinjam)' : '❌ Tidak';
                            }
                            
                            let bgColor = 'bg-blue-50 border-blue-400';
                            if (field === 'is_borrowed' || field === 'return_date') {
                                bgColor = 'bg-purple-50 border-purple-400';
                            }
                            
                            html += '<div class="' + bgColor + ' p-3 rounded-lg border-l-4">';
                            html += '<span class="font-semibold text-slate-800">' + displayName + '</span><br>';
                            html += '<span class="text-slate-600 text-xs">Awalnya: <strong>' + fromVal + '</strong></span><br>';
                            html += '<span class="text-slate-600 text-xs">Jadi: <strong>' + toVal + '</strong></span>';
                            html += '</div>';
                        }
                    }
                    
                    if (!hasChanges) {
                        html += '<div class="text-slate-500 italic text-center py-4">Tidak ada perubahan</div>';
                    }
                }
            }
            // HAPUS ACTION
            else if (action === 'Hapus') {
                titleIcon = '🗑️';
                titleText = 'Data Stok Keluar yang Dihapus';
                
                const fieldsToShow = ['item_name', 'date', 'quantity', 'outgoing_destination'];
                const fieldNames = {
                    'item_name': 'Nama Barang',
                    'date': 'Tanggal',
                    'quantity': 'Jumlah',
                    'outgoing_destination': 'Tujuan'
                };

                for (const field of fieldsToShow) {
                    if (detailsObj[field] !== undefined) {
                        const value = detailsObj[field] || '-';
                        html += '<div class="bg-rose-50 p-3 rounded-lg border-l-4 border-rose-400">';
                        html += '<span class="font-semibold text-rose-700">' + fieldNames[field] + ':</span><br>';
                        html += '<span class="text-slate-700">' + value + '</span>';
                        html += '</div>';
                    }
                }
            }
            // KEMBALIKAN ACTION (Mark as returned)
            else if (action === 'Kembalikan') {
                titleIcon = '✅';
                titleText = 'Barang Dipulangkan';
                
                const fieldsToShow = ['item_name', 'quantity', 'returned_at'];
                const fieldNames = {
                    'item_name': 'Nama Barang',
                    'quantity': 'Jumlah',
                    'returned_at': 'Waktu Pengembalian'
                };

                for (const field of fieldsToShow) {
                    if (detailsObj[field] !== undefined) {
                        const value = detailsObj[field] || '-';
                        html += '<div class="bg-emerald-50 p-3 rounded-lg border-l-4 border-emerald-400">';
                        html += '<span class="font-semibold text-emerald-700">' + fieldNames[field] + ':</span><br>';
                        html += '<span class="text-slate-700">' + value + '</span>';
                        html += '</div>';
                    }
                }
            }

            html += '</div>';

            Swal.fire({
                title: titleIcon + ' ' + titleText,
                html: html,
                width: '500px',
                customClass: { 
                    popup: 'rounded-2xl', 
                    title: 'text-lg font-bold text-slate-800',
                    htmlContainer: 'text-left'
                }
            });
        }

        // Auto-submit search form on input change
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInputStockOut');
            const searchForm = document.getElementById('searchFormStockOut');
            const searchButton = document.querySelector('button[onclick="toggleSearchStockOut()"]');
            
            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    searchForm.submit();
                });

                // Close search form when clicking outside
                document.addEventListener('click', function(event) {
                    if (!searchForm.classList.contains('hidden') && 
                        !searchForm.contains(event.target) && 
                        !searchButton.contains(event.target)) {
                        searchForm.classList.add('hidden');
                    }
                });
            }
        });

        // Delete confirmation with SweetAlert
        function confirmDelete(event, name, type) {
            event.preventDefault();
            Swal.fire({
                title: `Hapus ${type}?`,
                html: `<p class="text-slate-600">Apakah Anda yakin ingin menghapus <strong>${name}</strong>?</p>
                       <p class="text-xs text-slate-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '🗑️ Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-xl font-bold text-slate-800',
                    htmlContainer: 'text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.closest('form').submit();
                }
            });
        }

        // Mark as returned confirmation
        function confirmMarkReturned(stockOutId, itemName) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                html: `<p class="text-slate-600">Apakah Anda yakin <strong>${itemName}</strong> sudah dikembalikan?</p>
                       <p class="text-xs text-slate-500 mt-2">Stok akan dipulihkan dan waktu pengembalian akan dicatat.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '✅ Konfirmasi',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-xl font-bold text-slate-800',
                    htmlContainer: 'text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send request to mark as returned
                    fetch(`/stock-outs/${stockOutId}/mark-returned`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#10b981'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message,
                                icon: 'error'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses pengembalian.',
                            icon: 'error'
                        });
                    });
                }
            });
        }
    </script>
</x-app-layout>