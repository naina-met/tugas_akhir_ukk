<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50">
        <div class="py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">

                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800">Daftar Barang</h1>
                            <p class="text-slate-500 mt-1">Kelola dan pantau semua barang dalam sistem inventaris</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <button onclick="toggleSearchItems()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-sky-300 hover:bg-sky-50 transition-all shadow-sm">
                                <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>

                            <form id="searchFormItems" action="{{ route('items.index') }}" method="GET" class="hidden">
                                <div class="relative w-72">
                                    <input type="text" name="search" id="searchInputItems" placeholder="Cari nama atau kode barang..." value="{{ request('search') }}"
                                           class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-10 bg-white shadow-lg focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </form>

                            @if(Auth::user()->role === 'superadmin')
                                <button onclick="toggleHistoryModal()" class="flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2.5 rounded-xl border border-indigo-200 hover:bg-indigo-200 transition font-medium shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Histori
                                </button>
                            @endif

                            <a href="{{ route('items.create') }}" class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                                + Tambah Barang
                            </a>

                            <a href="{{ route('export.items') }}" class="bg-emerald-100 text-emerald-700 px-6 py-2.5 rounded-xl border border-emerald-200 hover:bg-emerald-200 transition font-medium shadow-sm" onclick="return confirmExport();">
                                Export
                            </a>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-6 px-5 py-4 bg-gradient-to-r from-emerald-50 to-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 px-5 py-4 bg-gradient-to-r from-rose-50 to-rose-50 text-rose-700 rounded-xl border border-rose-200 shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gradient-to-r from-sky-50 to-sky-100 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">#</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Nama</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Kode</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Kategori</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Stok</th>
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Satuan</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Kondisi</th>
                                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Foto</th>
                                @endif
                                <th class="px-6 py-4 text-sm font-semibold text-slate-700">Dibuat Oleh</th>
                                <th class="px-6 py-4 text-sm font-semibold text-center text-slate-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            @forelse ($items as $index => $item)
                                <tr class="hover:bg-sky-50 transition-colors border-b border-slate-100">
                                    <td class="px-6 py-4 text-sm">{{ $items->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-800">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $item->code }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-sky-100 text-sky-700">
                                            {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($item->stock <= 0) bg-red-100 text-red-700
                                            @elseif($item->stock <= 10) bg-amber-100 text-amber-700
                                            @else bg-emerald-100 text-emerald-700 @endif">
                                            {{ $item->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $item->unit }}</td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-6 py-4">
                                            @php
                                                $conditionMap = [
                                                    'baik' => ['bg-emerald-100', 'text-emerald-700', 'Baik'],
                                                    'rusak_ringan' => ['bg-amber-100', 'text-amber-700', 'Rusak Ringan'],
                                                    'rusak_berat' => ['bg-rose-100', 'text-rose-700', 'Rusak Berat']
                                                ];
                                                $condition = $item->condition ?? 'baik';
                                                $colors = $conditionMap[$condition] ?? $conditionMap['baik'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colors[0] }} {{ $colors[1] }}">
                                                {{ $colors[2] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->photo)
                                                <a href="{{ asset('storage/' . $item->photo) }}" target="_blank" class="inline-flex items-center justify-center">
                                                    <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" class="h-12 w-12 rounded-lg object-cover hover:shadow-md transition cursor-pointer border border-slate-200">
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400">-</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="px-6 py-4">
                                        @if($item->user)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-xs font-bold text-white uppercase shadow-sm">
                                                    {{ substr($item->user->username, 0, 1) }}
                                                </div>
                                                <span class="text-xs font-medium text-slate-700">{{ $item->user->username }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>
                                  <td class="px-6 py-4">
    <div class="flex justify-center gap-2">
        @if(Auth::check())

            <a href="{{ route('items.edit', $item) }}"
                class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-200 rounded-lg text-xs font-medium transition">
                Ubah
            </a>

            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button"
                    onclick="confirmDelete(event, '{{ $item->name }}', 'item')"
                    class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 border border-rose-200 rounded-lg text-xs font-medium transition">
                    Hapus
                </button>
            </form>
        @endif
    </div>
</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '10' : '8' }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="text-slate-400 font-medium">Tidak ada barang ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if ($items->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-center">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'superadmin')
    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900/50 backdrop-blur-sm" onclick="toggleHistoryModal()"></div>
            <div class="relative inline-block w-full max-w-4xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Histori Aktivitas - Barang
                    </h3>
                    <button onclick="toggleHistoryModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
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
                                        <button type="button" onclick='showDetail(@json($log->details))' class="px-3 py-1.5 bg-slate-100 text-slate-600 hover:bg-slate-200 border border-slate-200 rounded-lg text-xs font-medium transition-all">
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
        function confirmExport() { return confirm('Apakah Anda yakin mau mengekspor dalam bentuk Excel?'); }
        function toggleSearchItems() {
            const form = document.getElementById('searchFormItems');
            const input = document.getElementById('searchInputItems');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) input.focus();
        }
        function toggleHistoryModal() {
            const modal = document.getElementById('historyModal');
            if (modal) modal.classList.toggle('hidden');
        }
        function showDetail(details) {
            Swal.fire({
                title: 'Detail Perubahan',
                html: '<pre class="text-left text-xs bg-slate-50 p-4 rounded-lg overflow-auto max-h-96">' + JSON.stringify(details, null, 2) + '</pre>',
                width: '600px',
                customClass: { popup: 'rounded-2xl', title: 'text-lg font-bold text-slate-800' }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInputItems');
            const searchForm = document.getElementById('searchFormItems');
            const searchButton = document.querySelector('button[onclick="toggleSearchItems()"]');
            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() { searchForm.submit(); });
                document.addEventListener('click', function(event) {
                    if (!searchForm.classList.contains('hidden') && !searchForm.contains(event.target) && !searchButton.contains(event.target)) {
                        searchForm.classList.add('hidden');
                    }
                });
            }
        });
        function confirmDelete(event, name, type) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus Item?',
                html: '<p class="text-slate-600">Apakah Anda yakin ingin menghapus <strong>' + name + '</strong>?</p><p class="text-xs text-slate-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl', title: 'text-xl font-bold text-slate-800', htmlContainer: 'text-sm' }
            }).then((result) => { if (result.isConfirmed) event.target.closest('form').submit(); });
        }
    </script>
</x-app-layout>