<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Area Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 shadow sm:rounded-lg border-t-4 border-blue-500">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Formulir Peminjaman</h3>
                <form action="{{ route('user.pinjam.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilih Barang</label>
                            <select name="item_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} (Sisa: {{ $item->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                            <input type="number" name="jumlah" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Kembali (Max)</label>
                            <input type="date" name="tgl_kembali_max" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-blue-600 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700 transition">
                        Ajukan Pinjaman
                    </button>
                </form>
            </div>

            @if(auth()->user()->role === 'user')
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pengajuan Saya</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs">Barang</th>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs">Jumlah</th>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs">Tgl Pinjam</th>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs">Tgl Kembali</th> 
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs text-center">Status</th>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs text-center">Aksi</th>
                                <th class="px-4 py-3 border-b font-bold uppercase text-xs">Ket. Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myLoans as $loan)
                            <tr class="hover:bg-gray-50 transition text-sm">
                                <td class="px-4 py-3 border-b font-semibold text-gray-700">{{ $loan->item->name }}</td>
                                <td class="px-4 py-3 border-b text-gray-600">{{ $loan->jumlah }}</td>
                                <td class="px-4 py-3 border-b text-gray-600">
                                    {{ \Carbon\Carbon::parse($loan->tgl_pinjam)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 border-b text-gray-600">
                                    {{-- PERBAIKAN DI SINI: Tag TD dibungkus dengan benar --}}
                                    @if($loan->status == 'selesai' && $loan->updated_at)
                                        <span class="text-green-600 font-bold">
                                            {{ \Carbon\Carbon::parse($loan->updated_at)->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-b text-center">
                                    @if($loan->status == 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-[10px] font-bold">MENUNGGU</span>
                                    @elseif($loan->status == 'disetujui')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-[10px] font-bold">DIPINJAM</span>
                                    @elseif($loan->status == 'ditolak')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-[10px] font-bold">DITOLAK</span>
                                    @elseif($loan->status == 'menunggu_kembali')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-[10px] font-bold">PROSES BALIK</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-[10px] font-bold">SELESAI</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-b text-center">
                                    @if($loan->status == 'disetujui')
                                        <form action="{{ route('user.pinjam.kembalikan', $loan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan barang?')">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded shadow text-[10px] font-bold transition">
                                                KEMBALIKAN
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 border-b text-xs text-gray-500 italic">
                                    {{ $loan->status == 'ditolak' ? $loan->alasan_penolakan : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                    Belum ada riwayat peminjaman.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>