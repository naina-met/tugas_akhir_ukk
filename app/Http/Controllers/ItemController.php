<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $items = $query->latest()->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

  public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'code' => 'required|unique:items',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable',
        'unit' => 'required|in:pcs,box,kg,liter',
    ]);

    // simpan item dengan stock default 0
    $data = $request->all();
    $data['stock'] = 0;
    $item = Item::create($data);

    // path QR
    $qrPath = 'qr/items/item-' . $item->id . '.svg';

    // generate QR (SVG, TANPA imagick)
    Storage::disk('public')->put(
        $qrPath,
        QrCode::size(300)->generate(
            url('/lapor-kerusakan?item_id=' . $item->id)
        )
    );

    // simpan path ke DB
    $item->update([
        'qr_code' => $qrPath
    ]);

    return redirect()
        ->route('items.index')
        ->with('success', 'Item created + QR generated');
}
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items,code,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'unit' => 'required|in:pcs,box,kg,liter',
        ]);

        // update item tanpa mengubah stock
        $data = $request->all();
        unset($data['stock']);
        $item->update($data);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        // hapus file QR jika ada
        if ($item->qr_code) {
            Storage::disk('public')->delete($item->qr_code);
        }

        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
