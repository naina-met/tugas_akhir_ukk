<x-app-layout>
    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 tracking-tight mb-2">
                        📋 Kondisi Barang
                    </h1>
                    <p class="text-slate-600">
                        Lihat dan kelola semua kondisi barang dari sistem
                    </p>
                </div>

                <!-- Search Icon Button -->
                <button onclick="toggleSearchDamage()" class="p-3 rounded-xl bg-white border border-slate-200 hover:border-sky-300 hover:bg-sky-50 transition-all shadow-sm">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- Search Form (Hidden) -->
                <form id="searchFormDamage" action="{{ route('damage-reports.admin') }}" method="GET" class="hidden absolute top-32 right-6 z-10">
                    <div class="relative w-80">
                        <input type="text" 
                               name="search" 
                               id="searchInputDamage"
                               placeholder="Cari pengguna, barang atau deskripsi..." 
                               value="{{ request('search') }}"
                               class="w-full rounded-xl border border-slate-300 px-4 py-2.5 pl-10 shadow-lg
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 px-5 py-4 bg-emerald-50 text-emerald-700
                            rounded-xl border border-emerald-200 shadow-sm">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <!-- Button Tambah Laporan -->
            <div class="mb-8 flex justify-end gap-3">
                <a href="{{ route('export.damagereports') }}"
                   class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600
                          text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5
                          transition-all font-medium shadow-md"
                   onclick="return confirmExport();">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    📊 Export
                </a>
                <button onclick="openAddModal()"
                        class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-sky-600
                               text-white rounded-xl hover:shadow-lg hover:-translate-y-0.5
                               transition-all font-medium shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Laporan
                </button>
            </div>

            <!-- Stats Cards -->

            <!-- Table Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm text-left">

                        <!-- TABLE HEADER -->
                        <thead class="bg-gradient-to-r from-sky-50 to-sky-100 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-slate-700">Pengguna</th>
                                <th class="px-6 py-4 font-semibold text-slate-700">Barang</th>
                                <th class="px-6 py-4 font-semibold text-slate-700">Kondisi</th>
                                <th class="px-6 py-4 font-semibold text-slate-700">Deskripsi</th>
                                <th class="px-6 py-4 font-semibold text-slate-700 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <!-- TABLE BODY -->
                        <tbody class="text-slate-700">
                            @forelse($reports as $report)
                                <tr class="hover:bg-sky-50 transition-colors border-b border-slate-100">

                                    {{-- USER --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center text-xs font-bold text-sky-600">
                                                {{ strtoupper(substr($report->user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-800">
                                                    @if($search && stripos($report->user->username, $search) !== false)
                                                        {!! preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200 underline">$1</mark>', $report->user->username) !!}
                                                    @else
                                                        {{ $report->user->username ?? '-' }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-slate-500">{{ $report->user->name }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- ITEM --}}
                                    <td class="px-6 py-4">
                                        @if($report->item)
                                            <div class="flex items-center gap-3">
                                                @if($report->item->photo)
                                                    <img
                                                        src="{{ asset('storage/' . $report->item->photo) }}"
                                                        class="w-10 h-10 object-cover rounded-lg border border-slate-200"
                                                        alt="Item"
                                                    >
                                                @endif
                                                <span class="font-medium text-slate-800">
                                                    @if($search && stripos($report->item->name, $search) !== false)
                                                        {!! preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200 underline">$1</mark>', $report->item->name) !!}
                                                    @else
                                                        {{ $report->item->name }}
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    {{-- KONDISI --}}
                                    <td class="px-6 py-4">
                                        <div class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold
                                            @if($report->condition === 'baik')
                                                bg-green-100 text-green-700
                                            @elseif($report->condition === 'rusak_ringan')
                                                bg-yellow-100 text-yellow-700
                                            @else
                                                bg-red-100 text-red-700
                                            @endif
                                        ">
                                            @if($report->condition === 'baik')
                                                ✓ Baik
                                            @elseif($report->condition === 'rusak_ringan')
                                                ⚠ Rusak Ringan
                                            @else
                                                🔴 Rusak Berat
                                            @endif
                                        </div>
                                    </td>

                                    {{-- DESKRIPSI --}}
                                    <td class="px-6 py-4">
                                        <p class="text-slate-600 line-clamp-2 max-w-xs">
                                            @if($search && stripos($report->description, $search) !== false)
                                                {!! preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark class="bg-yellow-200 underline">$1</mark>', $report->description) !!}
                                            @else
                                                {{ $report->description }}
                                            @endif
                                        </p>
                                    </td>

                                    {{-- LOKASI --}}
                                    

                                    

                                    {{-- AKSI --}}
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <button onclick="openModal({{ $report->id }}, '{{ $report->condition }}', '{{ $report->description }}')"
                                                    class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200
                                                           border border-blue-200 rounded-lg text-xs font-medium transition">
                                                ✏️ Ubah
                                            </button>

                                            <!-- View Photo Button -->
                                            @if($report->photo_report)
                                                <a href="{{ asset('storage/' . $report->photo_report) }}" 
                                                   target="_blank"
                                                   class="px-3 py-1.5 bg-purple-100 text-purple-700 hover:bg-purple-200
                                                          border border-purple-200 rounded-lg text-xs font-medium transition">
                                                    📸 Foto
                                                </a>
                                            @endif

                                            <!-- Delete Button -->
                                            <form action="{{ route('damage-reports.destroy', $report->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        onclick="confirmDelete(event, 'Laporan #{{ $report->id }}', 'kondisi barang')"
                                                        class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200
                                                               border border-red-200 rounded-lg text-xs font-medium transition">
                                                    🗑️ Hapus
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
                                            <p class="text-slate-400 font-medium">Belum ada laporan kondisi barang</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if ($reports->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-center">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Edit Kondisi Barang</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Kondisi Barang
                    </label>
                    <select id="conditionSelect" name="condition" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="baik">✓ Baik</option>
                        <option value="rusak_ringan">⚠ Rusak Ringan</option>
                        <option value="rusak_berat">🔴 Rusak Berat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="descriptionInput" name="description" rows="4" required
                              class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"></textarea>
                </div>

                <!-- Photo Field - Only show for Rusak Ringan or Rusak Berat -->
                <div id="editPhotoSection" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Foto Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="editPhotoInput" name="photo_report" accept="image/*"
                           class="w-full text-sm text-slate-600
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-sky-100 file:text-sky-700
                                  hover:file:bg-sky-200 transition">
                    <p class="text-xs text-slate-500 mt-1">⚠️ Wajib upload foto untuk kondisi rusak</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()"
                            class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl
                                   hover:bg-slate-200 font-medium transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-sky-600 text-white rounded-xl
                                   hover:bg-sky-700 font-medium transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 my-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Tambah Kondisi Barang</h2>

            <form action="{{ route('damage-reports.admin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Item (Opsional)
                    </label>
                    <select name="item_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="">-- Pilih Item --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Kondisi Barang
                    </label>
                    <select name="condition" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="baik">✓ Baik</option>
                        <option value="rusak_ringan">⚠ Rusak Ringan</option>
                        <option value="rusak_berat">🔴 Rusak Berat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="description" rows="3" required
                              class="w-full rounded-xl border border-slate-300 px-4 py-2.5
                                     focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400"></textarea>
                </div>

                <!-- Photo Field - Only show for Rusak Ringan or Rusak Berat -->
                <div id="photoSection" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Foto Kerusakan <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="photoInput" name="photo_report" accept="image/*"
                           class="w-full text-sm text-slate-600
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-medium
                                  file:bg-sky-100 file:text-sky-700
                                  hover:file:bg-sky-200 transition">
                    <p class="text-xs text-slate-500 mt-1">⚠️ Wajib upload foto untuk kondisi rusak</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddModal()"
                            class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl
                                   hover:bg-slate-200 font-medium transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-sky-600 text-white rounded-xl
                                   hover:bg-sky-700 font-medium transition">
                        Tambah Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(reportId, condition, description) {
            document.getElementById('editForm').action = `/admin/laporan-kerusakan/${reportId}`;
            document.getElementById('conditionSelect').value = condition;
            document.getElementById('descriptionInput').value = description;
            document.getElementById('editModal').classList.remove('hidden');
            setupEditConditionListener();
            updateEditPhotoVisibility();
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Setup event listener for condition select in edit modal
        function setupEditConditionListener() {
            const conditionSelect = document.getElementById('conditionSelect');
            if (conditionSelect) {
                conditionSelect.addEventListener('change', function() {
                    updateEditPhotoVisibility();
                });
            }
        }

        // Update photo visibility based on condition in edit modal
        function updateEditPhotoVisibility() {
            const condition = document.getElementById('conditionSelect').value;
            const photoSection = document.getElementById('editPhotoSection');
            const photoInput = document.getElementById('editPhotoInput');
            
            if (condition === 'rusak_ringan' || condition === 'rusak_berat') {
                photoSection.classList.remove('hidden');
                photoInput.required = true;
            } else {
                photoSection.classList.add('hidden');
                photoInput.required = false;
                photoInput.value = '';
            }
        }

        // Setup event listener for condition select when modal opens
        function setupConditionListener() {
            const conditionSelect = document.querySelector('#addModal select[name="condition"]');
            if (conditionSelect) {
                conditionSelect.addEventListener('change', function() {
                    const photoSection = document.getElementById('photoSection');
                    const photoInput = document.getElementById('photoInput');
                    
                    if (this.value === 'rusak_ringan' || this.value === 'rusak_berat') {
                        photoSection.classList.remove('hidden');
                        photoInput.required = true;
                    } else {
                        photoSection.classList.add('hidden');
                        photoInput.required = false;
                        photoInput.value = '';
                    }
                });
            }
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            setupConditionListener();
            document.querySelector('#addModal form').reset();
            document.getElementById('photoSection').classList.add('hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        document.getElementById('addModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        function toggleSearchDamage() {
            const form = document.getElementById('searchFormDamage');
            const input = document.getElementById('searchInputDamage');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                input.focus();
            }
        }

        // Auto-submit search form on input change
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInputDamage');
            const searchForm = document.getElementById('searchFormDamage');
            const searchButton = document.querySelector('button[onclick="toggleSearchDamage()"]');
            
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

        function confirmExport() {
            event.preventDefault();
            const exportUrl = event.target.href;
            Swal.fire({
                title: 'Export Data?',
                text: 'Unduh data kondisi barang dalam format Excel',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '✓ Unduh',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    title: 'text-xl font-bold text-slate-800',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = exportUrl;
                }
            });
            return false;
        }
    </script>

</x-app-layout>
