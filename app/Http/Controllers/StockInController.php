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
        
        // Fetch activity logs for Stock In module
        $activityLogs = ActivityLog::where('module', 'Stock In')
            ->with('user')
            ->latest()
            ->get();
        
        return view('stock_ins.index', compact('stockIns', 'activityLogs'));
    }

    public function create()
    {
        $items = Item::all();
        return view('stock_ins.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'incoming_source' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stockIn = StockIn::create([
            'date' => now(),
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
            'details' => json_encode([
                'stock_in_id' => $stockIn->id,
                'item_id' => $request->item_id,
                'item_name' => $item->name,
                'date' => now()->format('Y-m-d H:i:s'),
                'quantity' => $request->quantity,
                'incoming_source' => $request->incoming_source,
                'description' => $request->description,
            ]),
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
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'incoming_source' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Capture original values for comparison
        $originalData = $stockIn->getOriginal();

        // rollback stok lama
        $oldItem = Item::findOrFail($stockIn->item_id);
        $oldItem->stock -= $stockIn->quantity;
        $oldItem->save();

        // update data
        $stockIn->update([
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'incoming_source' => $request->incoming_source,
            'description' => $request->description,
        ]);

        // tambah stok baru
        $newItem = Item::findOrFail($request->item_id);
        $newItem->stock += $request->quantity;
        $newItem->save();

        // Track changes
        $changes = [];
        $fieldsToCheck = ['quantity', 'incoming_source', 'description'];
        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $request->input($field);
            if ($oldValue !== $newValue) {
                $changes[$field] = [
                    'from' => $oldValue,
                    'to' => $newValue
                ];
            }
        }

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit',
            'module' => 'Stock In',
            'item_name' => $newItem->name,
            'details' => json_encode([
                'stock_in_id' => $stockIn->id,
                'item_id' => $request->item_id,
                'changes' => $changes,
            ]),
        ]);

        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock in updated successfully.');
    }

    public function destroy(StockIn $stockIn)
    {
        $item = Item::findOrFail($stockIn->item_id);
        $item->stock -= $stockIn->quantity;
        $item->save();
        $itemName = $item->name;
        
        // Capture data before deletion
        $stockInData = [
            'stock_in_id' => $stockIn->id,
            'item_name' => $itemName,
            'date' => $stockIn->date,
            'quantity' => $stockIn->quantity,
            'incoming_source' => $stockIn->incoming_source,
            'description' => $stockIn->description,
        ];
        
        // Log activity before deletion
        ActivityLog::create([
            'user_id' => Auth::id(), 
            'action' => 'Hapus', 
            'module' => 'Stock In', 
            'item_name' => $itemName, 
            'details' => json_encode($stockInData)
        ]);
        
        $stockIn->delete();

        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock in deleted successfully.');
    }
}
