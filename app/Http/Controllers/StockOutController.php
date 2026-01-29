<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with('item', 'user');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('outgoing_destination', 'like', "%{$search}%");
        }
        
        $stockOuts = $query->latest()->paginate(10);

        return view('stock_outs.index', compact('stockOuts'));
    }

    public function create()
    {
        $items = Item::all();
        return view('stock_outs.create', compact('items'));
    }

    public function store(Request $request)
    {
        // ✅ VALIDASI AMAN (SINKRON DENGAN DROPDOWN)
        $validated = $request->validate([
            'date' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'outgoing_destination' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['quantity'] > $item->stock) {
            return back()
                ->withErrors([
                    'quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $item->stock
                ])
                ->withInput();
        }

        $item->stock -= $validated['quantity'];
        $item->save();

        StockOut::create([
            'date' => $validated['date'],
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'outgoing_destination' => $validated['outgoing_destination'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Stock out recorded successfully.');
    }

    public function edit(StockOut $stockOut)
    {
        $items = Item::all();
        return view('stock_outs.edit', compact('stockOut', 'items'));
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'outgoing_destination' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $oldItem = Item::findOrFail($stockOut->item_id);
        $newItem = Item::findOrFail($validated['item_id']);

        $oldItem->stock += $stockOut->quantity;
        $oldItem->save();

        if ($validated['quantity'] > $newItem->stock) {
            return back()
                ->withErrors([
                    'quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $newItem->stock
                ])
                ->withInput();
        }

        $newItem->stock -= $validated['quantity'];
        $newItem->save();

        $stockOut->update([
            'date' => $validated['date'],
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'outgoing_destination' => $validated['outgoing_destination'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Stock out updated successfully.');
    }

    public function destroy(StockOut $stockOut)
    {
        $item = Item::findOrFail($stockOut->item_id);
        $item->stock += $stockOut->quantity;
        $item->save();

        $stockOut->delete();

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Stock out deleted successfully.');
    }
}
