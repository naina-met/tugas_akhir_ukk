<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-sky-50 via-white to-sky-100 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-slate-800">
                    🏷️ {{ isset($category) ? 'Ubah Kategori' : 'Tambah Kategori' }}
                </h1>
                <p class="text-slate-500 mt-1">
                    {{ isset($category) ? 'Perbarui informasi kategori' : 'Tambahkan kategori barang baru' }}
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 px-5 py-4 bg-rose-50 text-rose-700 rounded-xl border border-rose-200 shadow-sm">
                    <div class="font-semibold mb-2">Terjadi Kesalahan:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">
                <form method="POST"
                      action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}"
                      id="categoryForm"
                      class="space-y-6">
                    @csrf
                    @if (isset($category))
                        @method('PUT')
                    @endif

                    <!-- JENIS BARANG -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📦 Jenis Barang
                        </label>
                        <select name="jenis_barang_id" id="jenis_barang_id" required 
                                class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">
                            <option value="">-- Pilih Jenis Barang --</option>
                            @foreach ($jenisBarangs as $jenis)
                                <option value="{{ $jenis->id }}"
                                    {{ (isset($category) && $category->jenis_barang_id == $jenis->id) || old('jenis_barang_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_barang_id')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NAMA KATEGORI -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📝 Nama Kategori
                        </label>
                        <input
                            name="name"
                            type="text"
                            value="{{ old('name', $category->name ?? '') }}"
                            placeholder="Contoh: Proyektor, Kertas A4, AC, Pen Merah, dll"
                            required
                            class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition"/>
                        @error('name')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DESKRIPSI -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2.5">
                            📄 Deskripsi
                        </label>
                        <textarea name="description"
                                  rows="4"
                                  placeholder="Deskripsi kategori barang..."
                                  class="w-full px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 justify-end pt-6">
                        <a href="{{ route('categories.index') }}"
                           class="px-6 py-2.5 rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition font-medium">
                            Batal
                        </a>
                        <button type="submit"
                                id="submitBtn"
                                class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-sky-500 to-sky-600 text-white hover:shadow-lg hover:-translate-y-0.5 transition-all font-medium">
                            {{ isset($category) ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    $('#jenis_barang_id').change(function(){
        let jenis_id = $(this).val();
        
        $.get('/get-kelompok/' + jenis_id, function(data){
            let html = '<option value="">-- Pilih Kelompok Barang --</option>';
            
            data.forEach(e => {
                html += `<option value="${e.id}">${e.name}</option>`;
            });
            
            $('#kelompok_barang_id').html(html);
        });
    });
});
</script>

</x-app-layout>
