<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-indigo-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Histori Aktivitas Sistem
                </h1>
                <p class="text-slate-500 mt-1">Pantau semua perubahan data di sistem - Barang, Stock In, Stock Out, dan Kategori</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 mb-8">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Module Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Modul</label>
                            <select name="module" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option value="">-- Semua Modul --</option>
                                <option value="Items" {{ request('module') === 'Items' ? 'selected' : '' }}>Barang</option>
                                <option value="Stock In" {{ request('module') === 'Stock In' ? 'selected' : '' }}>Stock Masuk</option>
                                <option value="Stock Out" {{ request('module') === 'Stock Out' ? 'selected' : '' }}>Stock Keluar</option>
                                <option value="Categories" {{ request('module') === 'Categories' ? 'selected' : '' }}>Kategori</option>
                            </select>
                        </div>

                        <!-- Action Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Aksi</label>
                            <select name="action" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option value="">-- Semua Aksi --</option>
                                <option value="Tambah" {{ request('action') === 'Tambah' ? 'selected' : '' }}>Tambah</option>
                                <option value="Edit" {{ request('action') === 'Edit' ? 'selected' : '' }}>Edit</option>
                                <option value="Hapus" {{ request('action') === 'Hapus' ? 'selected' : '' }}>Hapus</option>
                            </select>
                        </div>

                        <!-- User Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pengguna</label>
                            <select name="user_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option value="">-- Semua Pengguna --</option>
                                @forelse($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->username }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>

                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Cari Item</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama item..." class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"/>
                        </div>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('activity-logs.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition font-medium text-sm">
                            Reset Filter
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition font-medium text-sm">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Activity Table -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gradient-to-r from-indigo-50 to-indigo-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Waktu</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Pengguna</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Modul</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Aksi</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Item</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($activityLogs as $log)
                            <tr class="hover:bg-indigo-50 transition">
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $log->created_at->format('d M Y') }}<br>
                                    <span class="text-xs text-slate-400">{{ $log->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-xs font-bold text-white uppercase">
                                            {{ substr($log->user->username ?? 'NA', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-slate-700">{{ $log->user->username ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->action === 'Tambah')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Tambah</span>
                                    @elseif($log->action === 'Edit')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Edit</span>
                                    @elseif($log->action === 'Hapus')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">Hapus</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $log->item_name }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500">
                                    <details>
                                        <summary class="cursor-pointer text-blue-600 hover:text-blue-700">Lihat sisanya</summary>
                                        <pre class="mt-2 p-2 bg-slate-50 rounded text-xs overflow-x-auto">{{ json_encode(json_decode($log->details), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-slate-400 font-medium">Tidak ada aktivitas ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($activityLogs->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $activityLogs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
