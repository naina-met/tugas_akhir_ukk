<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\Item;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $query = StockIn::with('item', 'user');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('incoming_source', 'like', "%{$search}%");
        }
        
        $stockIns = $query->latest()->paginate(10);
        return view('stock_ins.index', compact('stockIns'));
    }

    public function create()
    {
        $items = Item::all();
        return view('stock_ins.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'incoming_source' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stockIn = StockIn::create([
            'date' => $request->date,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'incoming_source' => $request->incoming_source,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        $item = Item::findOrFail($request->item_id);
        $item->stock += $request->quantity;
        $item->save();

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah',
            'module' => 'Stock In',
            'item_name' => $item->name,
            'details' => json_encode(['stock_in_id' => $stockIn->id, 'quantity' => $request->quantity]),
        ]);

        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock in added successfully.');
    }

    public function edit(StockIn $stockIn)
    {
        $items = Item::all();
        return view('stock_ins.edit', compact('stockIn', 'items'));
    }

    public function update(Request $request, StockIn $stockIn)
    {
        $request->validate([
            'date' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'incoming_source' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // rollback stok lama
        $oldItem = Item::findOrFail($stockIn->item_id);
        $oldItem->stock -= $stockIn->quantity;
        $oldItem->save();

        // update data
        $stockIn->update([
            'date' => $request->date,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'incoming_source' => $request->incoming_source,
            'description' => $request->description,
        ]);

        // tambah stok baru
        $newItem = Item::findOrFail($request->item_id);
        $newItem->stock += $request->quantity;
        $newItem->save();

        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock in updated successfully.');
    }

    public function destroy(StockIn $stockIn)
    {
        $item = Item::findOrFail($stockIn->item_id);
        $item->stock -= $stockIn->quantity;
        $item->save();
        $itemName = $item->name;
        ActivityLog::create(['user_id' => Auth::id(), 'action' => 'Hapus', 'module' => 'Stock In', 'item_name' => $itemName, 'details' => json_encode(['stock_in_id' => $stockIn->id])]);        $stockIn->delete();

        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock in deleted successfully.');
    }
}
