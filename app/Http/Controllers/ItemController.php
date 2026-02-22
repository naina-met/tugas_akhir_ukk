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
        $categories = Category::orderBy('name')->get()->unique('name')->values();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'unit' => 'required|in:pcs,box,kg,liter',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'category_id' => $item->category_id,
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
        $categories = Category::orderBy('name')->get()->unique('name')->values();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items,code,' . $item->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'unit' => 'required|in:pcs,box,kg,liter',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        unset($data['stock']);

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
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit',
            'module' => 'Items',
            'item_name' => $item->name,
            'details' => json_encode([
                'item_id' => $item->id,
                'code' => $item->code,
                'changes' => $data,
            ]),
        ]);

        return redirect()
            ->route('items.index')
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        if ($item->qr_code) {
            Storage::disk('public')->delete($item->qr_code);
        }

        if ($item->photo && Storage::disk('public')->exists($item->photo)) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return redirect()
            ->route('items.index')
            ->with('success', 'Item deleted successfully.');
    }
}
