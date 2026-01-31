<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-sky-800">
            Laporan Barang Masuk & Keluar
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-sky-50/50 via-white to-slate-50">
        <div class="container mx-auto px-4 py-6 max-w-7xl">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-sky-500 rounded-xl flex items-center justify-center shadow-md shadow-sky-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">Laporan Inventaris</h1>
                        <p class="text-sm text-slate-500">
                            @if($isSuperAdmin)
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-sky-100 text-sky-700 rounded text-xs font-medium">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Mode Superadmin
                                </span>
                            @else
                                Aktivitas: <span class="font-medium text-sky-600">{{ $currentUser->username }}</span>
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
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 rounded-lg border border-emerald-100">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                        <span class="text-xs text-slate-500">Masuk</span>
                        <span class="text-sm font-bold text-emerald-600">{{ number_format($totalMasuk) }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 rounded-lg border border-rose-100">
                        <div class="w-1.5 h-1.5 bg-rose-500 rounded-full"></div>
                        <span class="text-xs text-slate-500">Keluar</span>
                        <span class="text-sm font-bold text-rose-600">{{ number_format($totalKeluar) }}</span>
                    </div>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 p-4 mb-6">
                <form action="{{ route('reports.index') }}" method="GET">
                    <div class="flex flex-wrap items-end gap-3">
                        
                        {{-- Periode Tanggal --}}
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col">
                                <label class="text-[10px] font-medium text-slate-400 uppercase tracking-wide mb-1">Dari Tanggal</label>
                                <input type="date" name="tanggal_dari" 
                                    value="{{ $filters['tanggal_dari'] ?? '' }}" 
                                    class="h-9 text-sm border border-slate-200 rounded-lg px-3 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white w-[140px]">
                            </div>
                            <span class="text-slate-300 mt-5">-</span>
                            <div class="flex flex-col">
                                <label class="text-[10px] font-medium text-slate-400 uppercase tracking-wide mb-1">Sampai Tanggal</label>
                                <input type="date" name="tanggal_sampai" 
                                    value="{{ $filters['tanggal_sampai'] ?? '' }}" 
                                    class="h-9 text-sm border border-slate-200 rounded-lg px-3 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white w-[140px]">
                            </div>
                        </div>

                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>

                        {{-- Barang --}}
                        <div class="flex flex-col">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-wide mb-1">Barang</label>
                            <select name="item_id" class="h-9 text-sm border border-slate-200 rounded-lg px-2 pr-7 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white min-w-[130px]">
                                <option value="">Semua</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ ($filters['item_id'] ?? '') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jenis --}}
                        <div class="flex flex-col">
                            <label class="text-[10px] font-medium text-slate-400 uppercase tracking-wide mb-1">Jenis</label>
                            <select name="jenis_transaksi" class="h-9 text-sm border border-slate-200 rounded-lg px-2 pr-7 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 bg-white min-w-[100px]">
                                <option value="">Semua</option>
                                <option value="IN" {{ ($filters['jenis_transaksi'] ?? '') == 'IN' ? 'selected' : '' }}>Masuk</option>
                                <option value="OUT" {{ ($filters['jenis_transaksi'] ?? '') == 'OUT' ? 'selected' : '' }}>Keluar</option>
                            </select>
                        </div>

                        {{-- Filter User - KHUSUS SUPERADMIN --}}
                        @if($isSuperAdmin && isset($users) && $users->count() > 0)
                        <div class="h-8 w-px bg-sky-300 hidden md:block"></div>
                        <div class="flex flex-col">
                            <label class="text-[10px] font-medium text-sky-600 uppercase tracking-wide mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                Filter User
                            </label>
                            <select name="user_id" class="h-9 text-sm border-2 border-sky-400 rounded-lg px-2 pr-7 focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-sky-50 min-w-[150px] font-medium text-sky-700">
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
                            <button type="submit" class="h-9 px-4 bg-sky-500 text-white text-sm font-medium rounded-lg hover:bg-sky-600 transition-colors shadow-sm flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('reports.index') }}" class="h-9 px-3 bg-white border border-slate-200 text-slate-500 text-sm font-medium rounded-lg hover:bg-slate-50 transition-colors flex items-center">
                                Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Table Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-700 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Daftar Transaksi
                    </h3>
                    <span class="px-2 py-1 bg-sky-100 text-sky-700 text-xs font-medium rounded">{{ $reports->count() }} data</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-sky-500 to-sky-400 text-white text-xs">
                                <th class="px-3 py-3 text-left font-semibold w-12">No</th>
                                <th class="px-3 py-3 text-left font-semibold w-28">Tanggal</th>
                                <th class="px-3 py-3 text-left font-semibold">Barang</th>
                                <th class="px-3 py-3 text-center font-semibold w-20">Jenis</th>
                                <th class="px-3 py-3 text-center font-semibold w-16">Qty</th>
                                <th class="px-3 py-3 text-left font-semibold w-32">Tujuan</th>
                                <th class="px-3 py-3 text-left font-semibold min-w-[200px]">Keterangan</th>
                                <th class="px-3 py-3 text-left font-semibold w-28">User</th>
                                <th class="px-3 py-3 text-center font-semibold w-16 bg-sky-600/30">Sisa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $i => $r)
                            <tr class="hover:bg-sky-50/30 transition-colors">
                                <td class="px-3 py-3 text-slate-400 text-xs">{{ $i + 1 }}</td>
                                <td class="px-3 py-3">
                                    <div class="text-slate-700 text-xs font-medium">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($r->tanggal)->format('H:i') }}</div>
                                </td>
                                <td class="px-3 py-3 font-medium text-slate-700 text-xs">{{ $r->barang }}</td>
                                <td class="px-3 py-3 text-center">
                                    @if($r->tipe == 'IN')
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-100 rounded uppercase">
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 text-[10px] font-bold text-rose-700 bg-rose-100 rounded uppercase">
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[32px] px-1.5 py-0.5 rounded text-xs font-bold
                                        {{ $r->tipe == 'IN' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $r->tipe == 'IN' ? '+' : '-' }}{{ $r->jumlah }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-slate-600 text-xs">{{ $r->tujuan ?? '-' }}</td>
                                <td class="px-3 py-3 text-slate-500 text-xs">
                                    <div class="whitespace-normal break-words">{{ $r->keterangan ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-5 h-5 rounded bg-sky-100 flex items-center justify-center text-[10px] font-bold text-sky-600 uppercase flex-shrink-0">
                                            {{ substr($r->user, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-medium text-slate-700 truncate">{{ $r->user }}</div>
                                            @if($isSuperAdmin && isset($r->user_role))
                                                <div class="text-[10px] {{ strtolower($r->user_role) === 'superadmin' ? 'text-sky-500' : 'text-slate-400' }} truncate">
                                                    {{ ucfirst($r->user_role) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center bg-sky-50/30">
                                    @php
                                        $sisa = $r->sisa_stock ?? 0;
                                    @endphp
                                    <span class="inline-flex items-center justify-center min-w-[32px] px-1.5 py-0.5 rounded text-xs font-bold
                                        @if($sisa == 0) bg-slate-100 text-slate-500
                                        @elseif($sisa <= 10) bg-amber-100 text-amber-700
                                        @else bg-sky-100 text-sky-700 @endif">
                                        {{ $sisa }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 font-medium">Tidak ada data ditemukan</p>
                                            <p class="text-slate-400 text-sm">Coba ubah filter atau reset pencarian</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Info --}}
                <div class="px-4 py-2.5 bg-slate-50/80 border-t border-slate-100 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Kolom Sisa menampilkan stok terkini sesuai halaman Barang
                    </span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
