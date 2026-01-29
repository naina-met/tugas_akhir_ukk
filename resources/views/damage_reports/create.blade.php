<x-app-layout>
    {{-- <!-- Navbar
    <nav class="bg-gradient-to-r from-sky-600 to-sky-700 shadow-lg mb-10">
        <div class="max-w-7xl mx-auto px-6 flex items-center h-16">
            <span class="text-white font-semibold tracking-wide">
                {{ Auth::user()->name }}
            </span>
        </div>
    </nav> --> --}}

    <!-- Page Background -->
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    📋 Lapor Kerusakan
                </h1>
                <p class="text-slate-500 mt-1">
                    Laporkan kerusakan barang dengan foto dan deskripsi detail
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-200 max-w-2xl">

                <form action="{{ route('damage-reports.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="p-8 space-y-6">

                    @csrf

                    <!-- Item -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📦 Item
                        </label>
                        <select name="item_id"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                       text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                       focus:border-sky-400 focus:outline-none transition">
                            <option value="">-- Pilih Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📄 Deskripsi
                        </label>
                        <textarea name="description"
                                  rows="4"
                                  placeholder="Jelaskan detail kerusakan barang..."
                                  required
                                  class="w-full rounded-lg border border-slate-300 px-4 py-2.5
                                         text-slate-700 bg-white focus:ring-2 focus:ring-sky-400
                                         focus:border-sky-400 focus:outline-none transition">{{ old('description', '') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kondisi Barang -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            🔍 Kondisi Barang
                        </label>
                        <div class="space-y-3 border border-slate-200 rounded-lg p-4 bg-slate-50">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="condition" value="baik" 
                                       {{ old('condition') == 'baik' ? 'checked' : '' }}
                                       required
                                       class="w-4 h-4 text-green-600 rounded cursor-pointer">
                                <span class="ml-3 text-sm text-slate-700 font-medium">✅ Baik (Tidak Ada Kerusakan)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="condition" value="rusak_ringan"
                                       {{ old('condition') == 'rusak_ringan' ? 'checked' : '' }}
                                       required
                                       class="w-4 h-4 text-amber-600 rounded cursor-pointer">
                                <span class="ml-3 text-sm text-slate-700 font-medium">⚠️ Rusak Ringan (Masih Bisa Digunakan)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="condition" value="rusak_berat"
                                       {{ old('condition') == 'rusak_berat' ? 'checked' : '' }}
                                       required
                                       class="w-4 h-4 text-rose-600 rounded cursor-pointer">
                                <span class="ml-3 text-sm text-slate-700 font-medium">💔 Rusak Berat (Perlu Penghapusan Stok)</span>
                            </label>
                        </div>
                        @error('condition')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto -->
                    <div id="photoContainer">
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📸 Foto Kerusakan
                        </label>
                        <input type="file"
                               name="photo_report"
                               accept="image/*"
                               class="w-full text-sm text-slate-600
                                      file:mr-4 file:py-2.5 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-medium
                                      file:bg-sky-100 file:text-sky-700
                                      hover:file:bg-sky-200 transition cursor-pointer">
                        <p class="text-sm text-slate-500 mt-2">(Opsional - Upload jika ingin melampirkan foto bukti)</p>
                        @error('photo_report')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3 justify-end pt-6">
                        <a href="{{ route('damage-reports.admin') }}"
                           class="px-6 py-2.5 rounded-lg bg-slate-200 text-slate-700
                                  border border-slate-300 hover:bg-slate-300 transition
                                  font-medium text-sm">
                            ❌ Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-sky-500 to-sky-600
                                       text-white shadow-md hover:shadow-lg hover:-translate-y-0.5
                                       transition-all font-medium text-sm">
                            ✅ Kirim Laporan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
