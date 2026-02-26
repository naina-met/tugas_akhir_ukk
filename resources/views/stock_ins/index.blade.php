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
                            <h1 class="text-3xl font-bold text-slate-800">📪 Stok Masuk</h1>
                            <p class="text-slate-500 mt-1">Kelola barang masuk ke dalam gudang</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Search Icon Button -->
                            <button onclick="toggleSearchStockIn()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 transition-all shadow-sm">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>

                            <!-- Search Form (Hidden) -->
                            <form id="searchFormStockIn" action="{{ route('stock-ins.index') }}" method="GET" class="hidden">
                                <div class="relative w-72">
                                    <input type="text" 
                                           name="search" 
                                           id="searchInputStockIn"
                                           placeholder="Cari nama item atau sumber..." 
                                           value="{{ request('search') }}"
                                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-10 bg-white shadow-lg
                                                  focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition">
                                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </input>
                                </div>
                            </form>

                            @if(strtolower(Auth::user()->role) === 'superadmin')
                            <button onclick="toggleHistoryModalStockIn()" class="flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2.5 rounded-xl border border-indigo-200 hover:bg-indigo-200 transition font-medium shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Histori
                            </button>
                            @endif

                            <a href="{{ route('stock-ins.create') }}"
                               class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-2 rounded-xl
                                      shadow-md hover:shadow-lg hover:-translate-y-0.5
                                      transition-all font-medium">
                                + Tambah Stok Masuk
                            </a>

                            <a href="{{ route('export.stockins') }}"
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

                <table class="w-full text-left border border-slate-300 border-collapse">

                    <!-- HEADER -->
                    <thead class="bg-gradient-to-r from-emerald-50 to-emerald-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">#</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Jumlah</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Sumber</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Keterangan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Pengguna</th>
                            @if(strtolower(Auth::user()->role) === 'admin')
                            <th class="px-6 py-4 text-sm font-semibold text-center text-slate-700">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="text-slate-700">
                        @forelse ($stockIns as $index => $stockIn)
                            <tr class="hover:bg-emerald-50 transition-colors border-b border-slate-100">
                                <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockIn->date->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $stockIn->item->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        +{{ $stockIn->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockIn->incoming_source }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockIn->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockIn->user->username }}</td>
                                @if(strtolower(Auth::user()->role) === 'admin')
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('stock-ins.edit', $stockIn) }}"
                                           class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200
                                                  border border-blue-200 rounded-lg text-xs font-medium transition">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('stock-ins.destroy', $stockIn) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete(event, '{{ $stockIn->item->name }}', 'barang masuk')"
                                                    class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200
                                                           border border-rose-200 rounded-lg text-xs font-medium transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ strtolower(Auth::user()->role) === 'admin' ? '8' : '7' }}" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                            <p class="text-slate-400 font-medium">Tidak ada data stok masuk ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                    <!-- Pagination -->
                    @if ($stockIns->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-center">
                            {{ $stockIns->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(strtolower(Auth::user()->role) === 'superadmin')
    <div id="historyModalStockIn" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="toggleHistoryModalStockIn()"></div>
            <div class="relative inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Histori Aktivitas - Stok Masuk
                    </h3>
                    <button onclick="toggleHistoryModalStockIn()" class="p-2 hover:bg-slate-100 rounded-lg transition">
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
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $log->item_name }}</td>
                                    <td class="px-4 py-3">
                                        <button type="button" onclick='showDetailStockIn(@json($log->details), "{{ $log->action }}")' class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 rounded-lg text-xs font-medium transition-all">
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

    <script>
        function confirmExport() {
            return confirm('Apakah Anda yakin mau mengekspor dalam bentuk Excel?');
        }

        function toggleSearchStockIn() {
            const form = document.getElementById('searchFormStockIn');
            const input = document.getElementById('searchInputStockIn');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                input.focus();
            }
        }

        function toggleHistoryModalStockIn() {
            const modal = document.getElementById('historyModalStockIn');
            if (modal) modal.classList.toggle('hidden');
        }

        function showDetailStockIn(details, action) {
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
                titleText = 'Data Stok Masuk yang Ditambahkan';
                
                const fieldsToShow = ['date', 'item_name', 'quantity', 'incoming_source', 'description'];
                const fieldNames = {
                    'date': 'Tanggal',
                    'item_name': 'Nama Barang',
                    'quantity': 'Jumlah',
                    'incoming_source': 'Sumber',
                    'description': 'Keterangan'
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
            // EDIT ACTION
            else if (action === 'Edit') {
                titleIcon = '✏️';
                titleText = 'Perubahan Stok Masuk';
                
                if (detailsObj.changes && typeof detailsObj.changes === 'object') {
                    const changes = detailsObj.changes;
                    const fieldsToShow = ['date', 'quantity', 'incoming_source', 'description'];
                    
                    let hasChanges = false;
                    for (const field of fieldsToShow) {
                        if (changes[field] !== undefined) {
                            hasChanges = true;
                            const displayName = {
                                'date': 'Tanggal',
                                'quantity': 'Jumlah',
                                'incoming_source': 'Sumber',
                                'description': 'Keterangan'
                            }[field] || field;
                            
                            const changeData = changes[field];
                            const fromVal = (changeData.from || '-') === null ? '-' : changeData.from;
                            const toVal = (changeData.to || '-') === null ? '-' : changeData.to;
                            
                            html += '<div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-400">';
                            html += '<span class="font-semibold text-blue-700">' + displayName + '</span><br>';
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
                titleText = 'Data Stok Masuk yang Dihapus';
                
                const fieldsToShow = ['item_name', 'date', 'quantity', 'incoming_source'];
                const fieldNames = {
                    'item_name': 'Nama Barang',
                    'date': 'Tanggal',
                    'quantity': 'Jumlah',
                    'incoming_source': 'Sumber'
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
            const searchInput = document.getElementById('searchInputStockIn');
            const searchForm = document.getElementById('searchFormStockIn');
            const searchButton = document.querySelector('button[onclick="toggleSearchStockIn()"]');
            
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
    </script>
</x-app-layout>
