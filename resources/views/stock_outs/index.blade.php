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

                            <a href="{{ route('stock-outs.create') }}"
                               class="bg-gradient-to-r from-rose-500 to-rose-600 text-white px-6 py-2 rounded-xl
                                      shadow-md hover:shadow-lg hover:-translate-y-0.5
                                      transition-all font-medium">
                                + Tambah Stok Keluar
                            </a>

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

                <table class="w-full text-left border-collapse">

                    <!-- HEADER -->
                    <thead class="bg-gradient-to-r from-rose-50 to-rose-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">#</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Tanggal</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Barang</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Jumlah</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Tujuan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Keterangan</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Pengguna</th>
                            <th class="px-6 py-4 text-sm font-semibold text-center text-slate-700">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY -->
                    <tbody class="text-slate-700">
                        @forelse ($stockOuts as $index => $stockOut)
                            <tr class="hover:bg-rose-50 transition-colors border-b border-slate-100">
                                <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->date->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $stockOut->item->name }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                        -{{ $stockOut->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($stockOut->outgoing_destination === 'Rusak')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                            💔 Rusak
                                        </span>
                                    @elseif($stockOut->outgoing_destination === 'Penjualan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            🛍️ Penjualan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                            🔧 Pemakaian Internal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->user->username }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('stock-outs.edit', $stockOut) }}"
                                           class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200
                                                  border border-blue-200 rounded-lg text-xs font-medium transition">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete(event, '{{ $stockOut->item->name }}', 'barang keluar')"
                                                    class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200
                                                           border border-rose-200 rounded-lg text-xs font-medium transition">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
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
    </script>
</x-app-layout>
