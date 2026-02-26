<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\JenisBarang;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
   public function index(Request $request)
{
    $query = Category::with(['jenisBarang', 'items']);
    // ... (kode search kamu tetap)
    $categories = $query->latest()->paginate(10);

    // LOGIKA BARU: Kelompokkan barang berdasarkan Jenis (Modal/Habis Pakai)
    $itemsByJenis = [];
    $allData = Category::with(['jenisBarang', 'items'])->get();
    
    foreach ($allData as $cat) {
        $jenisName = $cat->jenisBarang->name ?? 'Lainnya';
        foreach ($cat->items as $item) {
            // Kita kumpulin semua barang ke dalam grup "Modal" atau "Habis Pakai"
            $itemsByJenis[$jenisName][] = $item->name;
        }
    }

    return view('categories.index', compact('categories', 'itemsByJenis'));
}

    public function create()
    {
        $jenisBarangs = JenisBarang::orderBy('name')->get()->unique('name')->values();
        return view('categories.create', compact('jenisBarangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang_id' => 'required|exists:jenis_barang,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'jenis_barang_id' => $request->jenis_barang_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah',
            'module' => 'Categories',
            'item_name' => $category->name,
            'details' => json_encode([
                'category_id' => $category->id,
                'jenis_barang_id' => $category->jenis_barang_id,
            ]),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        $jenisBarangs = JenisBarang::orderBy('name')->get()->unique('name')->values();
        return view('categories.edit', compact('category', 'jenisBarangs'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'jenis_barang_id' => 'required|exists:jenis_barang,id',
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $oldData = $category->getAttributes();
        
        $category->update([
            'jenis_barang_id' => $request->jenis_barang_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit',
            'module' => 'Categories',
            'item_name' => $category->name,
            'details' => json_encode([
                'category_id' => $category->id,
                'old_data' => $oldData,
                'new_data' => $category->getAttributes(),
            ]),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus',
            'module' => 'Categories',
            'item_name' => $categoryName,
            'details' => json_encode([
                'category_id' => $category->id,
            ]),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus');
    }

    // API endpoint to get categories by jenis barang (if needed for dropdowns)
    public function getCategoriesByJenis(JenisBarang $jenisBarang)
    {
        return response()->json($jenisBarang->categories);
    }
}
