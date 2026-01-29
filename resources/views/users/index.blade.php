<x-app-layout>
    <!-- Navbar -->
    {{-- <nav class="bg-transparent mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="text-sm text-slate-600 font-medium">
                {{ Auth::user()->name }}
            </div>
        </div>
    </nav> --}}

    <!-- Content -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">👥 Kelola Akun Admin</h1>
                        <p class="text-slate-500 mt-1">Kelola dan pantau semua akun administrator dalam sistem</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Search Icon Button -->
                        <button onclick="toggleSearchUsers()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-sky-300 hover:bg-sky-50 transition-all shadow-sm">
                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <!-- Search Form (Hidden) -->
                        <form id="searchFormUsers" action="{{ route('users.index') }}" method="GET" class="hidden">
                            <div class="relative w-72">
                                <input type="text" 
                                    name="search" 
                                    id="searchInputUsers"
                                    placeholder="Cari username atau email..." 
                                    value="{{ request('search') }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-10 bg-white shadow-lg focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                                <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </form>

                        <a href="{{ route('users.create') }}"
                            class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-2 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                            + Tambah Admin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 px-5 py-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <!-- HEADER -->
                    <thead class="bg-gradient-to-r from-sky-50 to-sky-100 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">#</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Nama Pengguna</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Akun</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-700">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-center text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <!-- BODY -->
                    <tbody class="text-slate-700">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-sky-50 transition-colors border-b border-slate-100">
                                <td class="px-6 py-4 text-sm">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    @if($search && stripos($user->username, $search) !== false)
                                        {!! preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200 underline">$1</mark>', $user->username) !!}
                                    @else
                                        {{ $user->username }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($search && stripos($user->email, $search) !== false)
                                        {!! preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200 underline">$1</mark>', $user->email) !!}
                                    @else
                                        {{ $user->email }}
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->status ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $user->status ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-3">
                                        <!-- EDIT -->
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="bg-sky-100 text-sky-700 border border-sky-200 hover:bg-sky-200 hover:text-sky-800 px-4 py-1.5 rounded-lg text-sm font-medium transition">
                                            Ubah
                                        </a>
                                        <!-- DELETE -->
                                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(event, '{{ $user->username }}', 'admin akun')"
                                                class="bg-rose-100 text-rose-700 border border-rose-200 hover:bg-rose-200 hover:text-rose-800 px-4 py-1.5 rounded-lg text-sm font-medium transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                        <p class="text-slate-400 font-medium">Tidak ada pengguna admin ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-center">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function toggleSearchUsers() {
            const form = document.getElementById('searchFormUsers');
            const input = document.getElementById('searchInputUsers');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                input.focus();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInputUsers');
            const searchForm = document.getElementById('searchFormUsers');
            const searchButton = document.querySelector('button[onclick="toggleSearchUsers()"]');
            
            if (searchInput && searchForm) {
                // Auto-submit search form on input change
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
