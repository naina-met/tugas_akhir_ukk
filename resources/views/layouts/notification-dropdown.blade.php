{{-- 
    File ini berisi contoh implementasi dropdown notifikasi.
    Anda bisa meng-include file ini di layout utama Anda (misal: navigation.blade.php)
    Pastikan variabel $pendingLoans tersedia dari View Composer atau controller.
--}}
<div x-data="{ openModal: false, loanId: null, actionUrl: '' }">
    {{-- Ganti bagian ini dengan ikon lonceng notifikasi Anda --}}
    <button class="relative p-2 text-gray-500 rounded-full hover:bg-gray-100 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        @if(isset($notifPinjam) && $notifPinjam > 0)
            <span class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">{{ $notifPinjam }}</span>
        @endif
    </button>

    {{-- Dropdown content --}}
    <div class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
        <div class="p-3 font-bold border-b text-gray-700">Permintaan Peminjaman</div>
        @forelse($pendingLoans as $loan)
            <div class="p-3 border-b hover:bg-gray-50">
                <p class="text-sm text-gray-600">
                    <span class="font-bold text-gray-800">{{ $loan->user->name }}</span> ingin meminjam
                    <span class="font-bold text-gray-800">{{ $loan->item->name }}</span> ({{ $loan->jumlah }} unit).
                </p>
                <div class="flex items-center justify-end gap-2 mt-2">
                    <form action="{{ route('admin.pinjam.approve', $loan->id) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini? Stok akan dikurangi.')">
                        @csrf
                        <button type="submit" class="px-2 py-1 text-xs font-semibold text-white bg-green-500 rounded hover:bg-green-600 transition-colors">Setuju</button>
                    </form>
                    <button @click="openModal = true; loanId = {{ $loan->id }}; actionUrl = '{{ route('admin.pinjam.reject', $loan->id) }}'" type="button" class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded hover:bg-red-600 transition-colors">Tolak</button>
                </div>
            </div>
        @empty
            <div class="p-4 text-sm text-center text-gray-500">Tidak ada permintaan baru.</div>
        @endforelse
    </div>

    {{-- Rejection Modal --}}
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50" @keydown.escape.window="openModal = false">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-xl" @click.away="openModal = false">
            <h3 class="text-lg font-bold text-gray-800">Alasan Penolakan</h3>
            <p class="mt-1 text-sm text-gray-600">Mohon berikan alasan mengapa peminjaman ini ditolak. Alasan ini akan ditampilkan kepada user.</p>
            <form :action="actionUrl" method="POST" class="mt-4">
                @csrf
                <textarea name="alasan_penolakan" rows="3" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Barang sedang digunakan untuk acara lain."></textarea>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors">Tolak Pinjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>