<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // TAMBAH user
        $query = Item::with(['category', 'user']);
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }
        
        $items = $query->latest()->paginate(10);
        
        // Load activity logs for history modal
        $activityLogs = ActivityLog::where('module', 'Items')
                                    ->with('user')
                                    ->latest()
                                    ->get();
        
        return view('items.index', compact('items', 'activityLogs'));
    }

    public function create()
    {
        // Only admin and superadmin can create items
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'superadmin'])) {
            return redirect()
                ->route('items.index')
                ->with('error', 'Anda tidak memiliki izin untuk menambahkan barang!');
        }

        $categories = Category::orderBy('name')->get()->unique('name')->values();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Only admin and superadmin can store items
        if (!in_array(strtolower(Auth::user()->role), ['admin', 'superadmin'])) {
            return redirect()
                ->route('items.index')
                ->with('error', 'Anda tidak memiliki izin untuk menambahkan barang!');
        }

        // Foto wajib jika kondisi rusak_ringan atau rusak_berat
        $photoRequired = in_array($request->condition, ['rusak_ringan', 'rusak_berat']);

        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'unit' => 'required|in:pcs,box,kg,liter',
            'photo' => $photoRequired ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['stock'] = 0;

        // 🔥 SIMPAN USER YANG INPUT
        $data['user_id'] = Auth::id();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');
            $data['photo'] = $photoPath;
        }

        $item = Item::create($data);

        $qrPath = 'qr/items/item-' . $item->id . '.svg';

        Storage::disk('public')->put(
            $qrPath,
            QrCode::size(300)->generate(
                url('/lapor-kerusakan?item_id=' . $item->id)
            )
        );

        $item->update([
            'qr_code' => $qrPath
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah',
            'module' => 'Items',
            'item_name' => $item->name,
            'details' => json_encode([
                'item_id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category_id' => $item->category_id,
                'description' => $item->description,
                'condition' => $item->condition,
                'unit' => $item->unit,
            ]),
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item created');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        // Only admin can edit items
        if (strtolower(Auth::user()->role) !== 'admin') {
            return redirect()
                ->route('items.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit barang!');
        }

        $categories = Category::orderBy('name')->get()->unique('name')->values();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        // Only admin can update items
        if (strtolower(Auth::user()->role) !== 'admin') {
            return redirect()
                ->route('items.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit barang!');
        }

        // Foto wajib jika kondisi rusak_ringan atau rusak_berat
        $photoRequired = in_array($request->condition, ['rusak_ringan', 'rusak_berat']);

        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items,code,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'unit' => 'required|in:pcs,box,kg,liter',
            'photo' => $photoRequired ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        unset($data['stock']);

        // Capture original values for comparison
        $originalData = $item->getOriginal();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($item->photo && Storage::disk('public')->exists($item->photo)) {
                Storage::disk('public')->delete($item->photo);
            }
            $photoPath = $request->file('photo')->store('items', 'public');
            $data['photo'] = $photoPath;
        }

        $item->update($data);

        // Log activity
        $changes = [];
        foreach ($data as $key => $newValue) {
            if ($key !== '_token' && $key !== '_method') {
                $oldValue = $originalData[$key] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[$key] = [
                        'from' => $oldValue,
                        'to' => $newValue
                    ];
                }
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit',
            'module' => 'Items',
            'item_name' => $item->name,
            'details' => json_encode([
                'item_id' => $item->id,
                'code' => $item->code,
                'changes' => $changes,
            ]),
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        // Only admin can delete items
        if (strtolower(Auth::user()->role) !== 'admin') {
            return redirect()
                ->route('items.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus barang!');
        }

        if ($item->qr_code) {
            Storage::disk('public')->delete($item->qr_code);
        }

        if ($item->photo && Storage::disk('public')->exists($item->photo)) {
            Storage::disk('public')->delete($item->photo);
        }

        // Capture item data before deletion
        $itemData = [
            'item_id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'category_id' => $item->category_id,
        ];

        $item->delete();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus',
            'module' => 'Items',
            'item_name' => $itemData['name'],
            'details' => json_encode($itemData),
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
