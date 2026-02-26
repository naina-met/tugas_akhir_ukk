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
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; }
        .animate-fade-in-left { animation: fadeInLeft 0.5s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.4s ease-out forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .stat-badge { opacity: 0; animation: fadeInUp 0.4s ease-out forwards; }
        .stat-badge:nth-child(1) { animation-delay: 0.1s; }
        .stat-badge:nth-child(2) { animation-delay: 0.2s; }
        .filter-card { opacity: 0; animation: fadeInUp 0.5s ease-out 0.2s forwards; }
        .table-card { opacity: 0; animation: scaleIn 0.5s ease-out 0.3s forwards; }
        .table-row { opacity: 0; animation: fadeInUp 0.3s ease-out forwards; }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%230ea5e9' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-sky-50/50 via-white to-slate-50 bg-pattern">
        <div class="container mx-auto px-4 py-8 max-w-7xl">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-fade-in-left">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-sky-400 to-sky-600 rounded-2xl flex items-center justify-center shadow-lg shadow-sky-200 hover:shadow-xl hover:scale-105 transition-all duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Laporan Inventaris</h1>
                        <p class="text-sm text-slate-500 mt-1">
                            @if($isSuperAdmin)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-sky-100 to-sky-50 text-sky-700 rounded-lg text-xs font-medium border border-sky-200">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Mode Superadmin - Akses Penuh
                                </span>
                            @else
                                Aktivitas: <span class="font-semibold text-sky-600">{{ $currentUser->username }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Quick Stats --}}
                @php
                    $totalMasuk = $reports->where('tipe', 'IN')->sum('jumlah');
                    $totalKeluar = $reports->where('tipe', 'OUT')->sum('jumlah');
                    $totalTransaksi = $reports->count();
                @endphp
                <div class="flex items-center gap-3">
                    <div class="stat-badge flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-emerald-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-300">
                        <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-slate-500">Masuk</span>
                        <span class="text-lg font-bold text-emerald-600">{{ number_format($totalMasuk) }}</span>
                    </div>
                    <div class="stat-badge flex items-center gap-2 px-4 py-2.5 bg-white rounded-xl border border-rose-100 shadow-sm hover:shadow-md hover:border-rose-200 transition-all duration-300">
                        <div class="w-2.5 h-2.5 bg-rose-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-slate-500">Keluar</span>
                        <span class="text-lg font-bold text-rose-600">{{ number_format($totalKeluar) }}</span>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="filter-card bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 mb-6 hover:shadow-md transition-shadow duration-300">
                <form action="{{ route('reports.index') }}" method="GET">
                    <div class="flex flex-wrap items-end gap-4">
                        
                        {{-- Periode Tanggal --}}
                        <div class="flex items-center gap-3">
                            <div class="flex flex-col">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                                <input type="date" name="tanggal_dari" 
                                    value="{{ $filters['tanggal_dari'] ?? '' }}" 
                                    class="h-10 text-sm border border-slate-200 rounded-xl px-4 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-slate-50 hover:bg-white transition-colors w-[150px]">
                            </div>
                            <span class="text-slate-300 mt-6 font-bold">-</span>
                            <div class="flex flex-col">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                                <input type="date" name="tanggal_sampai" 
                                    value="{{ $filters['tanggal_sampai'] ?? '' }}" 
                                    class="h-10 text-sm border border-slate-200 rounded-xl px-4 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-slate-50 hover:bg-white transition-colors w-[150px]">
                            </div>
                        </div>

                        <div class="h-10 w-px bg-slate-200 hidden md:block"></div>

                        {{-- Barang --}}
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Barang</label>
                            <select name="item_id" class="h-10 text-sm border border-slate-200 rounded-xl px-3 pr-8 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-slate-50 hover:bg-white transition-colors min-w-[140px]">
                                <option value="">Semua Barang</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ ($filters['item_id'] ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis --}}
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Jenis</label>
                            <select name="jenis_transaksi" class="h-10 text-sm border border-slate-200 rounded-xl px-3 pr-8 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-slate-50 hover:bg-white transition-colors min-w-[120px]">
                                <option value="">Semua Jenis</option>
                                <option value="IN" {{ ($filters['jenis_transaksi'] ?? '') == 'IN' ? 'selected' : '' }}>Masuk</option>
                                <option value="OUT" {{ ($filters['jenis_transaksi'] ?? '') == 'OUT' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>

                        {{-- Filter User - KHUSUS SUPERADMIN --}}
                        @if($isSuperAdmin && isset($users) && $users->count() > 0)
                        <div class="h-10 w-px bg-sky-300 hidden md:block"></div>
                        <div class="flex flex-col">
                            <label class="text-xs font-semibold text-sky-600 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Filter User
                            </label>
                            <select name="user_id" class="h-10 text-sm border-2 border-sky-300 rounded-xl px-3 pr-8 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-sky-50 hover:bg-sky-100 transition-colors min-w-[160px] font-medium text-sky-700">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ ($filters['user_id'] ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->username }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Buttons --}}
                        <div class="flex items-center gap-2 ml-auto">
                            <button type="submit" class="h-10 px-5 bg-gradient-to-r from-sky-500 to-sky-600 text-white text-sm font-semibold rounded-xl hover:from-sky-600 hover:to-sky-700 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('reports.index') }}" class="h-10 px-4 bg-white border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </a>
                            <a href="{{ route('export.reports', request()->all()) }}" 
       class="h-10 px-4 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        EXPORT EXCEL
    </a>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="table-card bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-white flex items-center justify-between">
                    <h3 class="font-semibold text-slate-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Daftar Transaksi
                    </h3>
                    <span class="px-3 py-1.5 bg-gradient-to-r from-sky-100 to-sky-50 text-sky-700 text-sm font-semibold rounded-lg border border-sky-200">{{ $reports->count() }} data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-sky-500 to-sky-400 text-white">
                                <th class="px-4 py-4 text-left font-semibold w-12">No</th>
                                <th class="px-4 py-4 text-left font-semibold w-32">Tanggal</th>
                                <th class="px-4 py-4 text-left font-semibold">Barang</th>
                                <th class="px-4 py-4 text-left font-semibold w-28">Kondisi</th>
                                <th class="px-4 py-4 text-center font-semibold w-24">Jenis</th>
                                <th class="px-4 py-4 text-center font-semibold w-20">Qty</th>
                                <th class="px-4 py-4 text-left font-semibold w-36">Tujuan</th>
                                <th class="px-4 py-4 text-left font-semibold min-w-[180px]">Keterangan</th>
                                <th class="px-4 py-4 text-left font-semibold w-32">User</th>
                                <th class="px-4 py-4 text-center font-semibold w-20 bg-sky-600/30">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $i => $r)
                            <tr class="table-row hover:bg-sky-50/50 transition-colors duration-200" style="animation-delay: {{ $i * 0.03 }}s">
                                <td class="px-4 py-4 text-slate-400 font-medium">{{ $i + 1 }}</td>
                                <td class="px-4 py-4">
                                    <div class="text-slate-700 font-medium">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</div>
                                    {{-- <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($r->tanggal)->format('H:i') }}</div> --}}
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $r->barang }}</td>
                                <td class="px-4 py-4">
    <div class="text-slate-600 font-medium">
        {{ $r->kondisi ?? '-' }}
    </div>
</td>
                                <td class="px-4 py-4 text-center">
                                    @if($r->tipe == 'IN')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-lg uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-rose-700 bg-rose-100 rounded-lg uppercase tracking-wide">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[40px] px-2 py-1 rounded-lg text-sm font-bold
                                        {{ $r->tipe == 'IN' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' }}">
                                        {{ $r->tipe == 'IN' ? '+' : '-' }}{{ $r->jumlah }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $r->tujuan ?? '-' }}</td>
                                <td class="px-4 py-4 text-slate-500">
                                    <div class="whitespace-normal break-words max-w-[200px]">{{ $r->keterangan ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-sky-400 to-sky-500 flex items-center justify-center text-xs font-bold text-white uppercase flex-shrink-0 shadow-sm">
                                            {{ substr($r->user, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-slate-700 truncate">{{ $r->user }}</div>
                                            @if($isSuperAdmin && isset($r->user_role))
                                                <div class="text-xs {{ strtolower($r->user_role) === 'superadmin' ? 'text-sky-500 font-medium' : 'text-slate-400' }} truncate">
                                                    {{ ucfirst($r->user_role) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center bg-sky-50/50">
                                    @php
                                        $sisa = $r->sisa_stock ?? 0;
                                    @endphp
                                    <span class="inline-flex items-center justify-center min-w-[40px] px-2 py-1 rounded-lg text-sm font-bold
                                        @if($sisa == 0) bg-slate-100 text-slate-500 border border-slate-200
                                        @elseif($sisa <= 10) bg-amber-100 text-amber-700 border border-amber-200
                                        @else bg-sky-100 text-sky-700 border border-sky-200 @endif">
                                        {{ $sisa }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 font-semibold">Tidak ada data ditemukan</p>
                                            <p class="text-slate-400 text-sm mt-1">Coba ubah filter atau reset pencarian</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Info --}}
                <div class="px-6 py-3 bg-gradient-to-r from-slate-50 to-white border-t border-slate-100 text-sm text-slate-500 flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Kolom <span class="font-semibold text-sky-600">Sisa</span> menampilkan stok setelah transaksi tersebut terjadi (running balance)
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
