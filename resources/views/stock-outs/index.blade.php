<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-fade-in-left { animation: fadeInLeft 0.5s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.4s ease-out forwards; }
        .animate-slide-down { animation: slideDown 0.3s ease-out forwards; }
        .header-section { opacity: 0; animation: fadeInLeft 0.5s ease-out forwards; }
        .table-card { opacity: 0; animation: scaleIn 0.5s ease-out 0.2s forwards; }
        .table-row { opacity: 0; animation: fadeInUp 0.3s ease-out forwards; }
        .success-alert { animation: slideDown 0.4s ease-out forwards; }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f43f5e' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-rose-50/30 via-white to-slate-50 bg-pattern">
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                <!-- Header Section -->
                <div class="header-section mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-rose-400 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-200 hover:shadow-xl hover:scale-105 transition-all duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-slate-800">Stok Keluar</h1>
                                <p class="text-slate-500 mt-1">Kelola barang keluar dari gudang</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- Search Icon Button -->
                            <button onclick="toggleSearchStockOut()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-rose-300 hover:bg-rose-50 transition-all shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>

                            <!-- Search Form (Hidden) -->
                            <form id="searchFormStockOut" action="{{ route('stock-outs.index') }}" method="GET" class="hidden animate-slide-down">
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
                               class="bg-gradient-to-r from-rose-500 to-rose-600 text-white px-6 py-2.5 rounded-xl
                                      shadow-md hover:shadow-lg hover:-translate-y-0.5
                                      transition-all font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah Stok Keluar
                            </a>

                            <a href="{{ route('export.stockouts') }}"
                               class="bg-white text-emerald-700 px-5 py-2.5 rounded-xl border border-emerald-200
                                      hover:bg-emerald-50 hover:border-emerald-300 transition-all font-semibold shadow-sm hover:shadow-md flex items-center gap-2"
                               onclick="return confirmExport();">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="success-alert mb-6 px-5 py-4 bg-gradient-to-r from-emerald-50 to-emerald-100 text-emerald-700
                                rounded-2xl border border-emerald-200 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Table Card -->
                <div class="table-card bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-rose-50 to-white flex items-center justify-between">
                        <h3 class="font-semibold text-slate-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Daftar Stok Keluar
                        </h3>
                        <span class="px-3 py-1.5 bg-gradient-to-r from-rose-100 to-rose-50 text-rose-700 text-sm font-semibold rounded-lg border border-rose-200">
                            {{ $stockOuts->total() }} data
                        </span>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <!-- HEADER -->
                        <thead class="bg-gradient-to-r from-rose-500 to-rose-400 text-white">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold">#</th>
                                <th class="px-6 py-4 text-sm font-semibold">Tanggal</th>
                                <th class="px-6 py-4 text-sm font-semibold">Barang</th>
                                <th class="px-6 py-4 text-sm font-semibold">Jumlah</th>
                                <th class="px-6 py-4 text-sm font-semibold">Tujuan</th>
                                <th class="px-6 py-4 text-sm font-semibold">Keterangan</th>
                                <th class="px-6 py-4 text-sm font-semibold">Pengguna</th>
                                <th class="px-6 py-4 text-sm font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>

                        <!-- BODY -->
                        <tbody class="text-slate-700 divide-y divide-slate-100">
                            @forelse ($stockOuts as $index => $stockOut)
                                <tr class="table-row hover:bg-rose-50/50 transition-colors duration-200" style="animation-delay: {{ $index * 0.03 }}s">
                                    <td class="px-6 py-4 text-sm">
    @switch($stockOut->outgoing_destination)

        @case('rusak')
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-100 text-rose-700 border border-rose-200">
                Rusak
            </span>
            @break

        @case('penjualan')
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                Penjualan
            </span>
            @break

        @case('peminjaman')
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700 border border-purple-200">
                Peminjaman
            </span>
            @break

        @case('pemakaian_internal')
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                Pemakaian Internal
            </span>
            @break

        @default
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                {{ $stockOut->outgoing_destination }}
            </span>
    @endswitch
</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $stockOut->description ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-rose-400 to-rose-500 flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm">
                                                {{ substr($stockOut->user->username, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-slate-700">{{ $stockOut->user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('stock-outs.edit', $stockOut) }}"
                                               class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100
                                                      border border-blue-200 rounded-lg text-xs font-semibold transition-all hover:shadow-sm">
                                                Edit
                                            </a>
                                            <form action="{{ route('stock-outs.destroy', $stockOut) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete(event, '{{ $stockOut->item->name }}', 'barang keluar')"
                                                        class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100
                                                               border border-rose-200 rounded-lg text-xs font-semibold transition-all hover:shadow-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="table-row hover:bg-rose-50/50 transition-colors duration-200">
    {{-- # --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOuts->firstItem() + $index }}
    </td>

    {{-- Tanggal --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOut->date->format('d-m-Y') }}
    </td>

    {{-- Barang --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOut->item->name }}
    </td>

    {{-- Jumlah --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOut->quantity }}
    </td>

    {{-- Tujuan --}}
    <td class="px-6 py-4 text-sm">
        @switch($stockOut->outgoing_destination)
            @case('rusak')
                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded">Rusak</span>
                @break
            @case('penjualan')
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded">Penjualan</span>
                @break
            @case('peminjaman')
                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded">Peminjaman</span>
                @break
            @case('pemakaian_internal')
                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded">Pemakaian Internal</span>
                @break
        @endswitch
    </td>

    {{-- Keterangan --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOut->description ?? '-' }}
    </td>

    {{-- Pengguna --}}
    <td class="px-6 py-4 text-sm">
        {{ $stockOut->user->username }}
    </td>

    {{-- Aksi --}}
    <td class="px-6 py-4 text-center">
        <a href="{{ route('stock-outs.edit', $stockOut) }}">Edit</a>
    </td>
</tr>

                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if ($stockOuts->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 bg-gradient-to-r from-slate-50 to-white flex justify-center">
                            {{ $stockOuts->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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

        // Auto-submit search form on input change with debounce
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInputStockOut');
            const searchForm = document.getElementById('searchFormStockOut');
            const searchButton = document.querySelector('button[onclick="toggleSearchStockOut()"]');
            let debounceTimer;
            
            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
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
                confirmButtonText: 'Ya, Hapus',
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
