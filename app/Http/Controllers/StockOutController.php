<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Item;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with(['item', 'user']);

        if ($request->filled('search')) {
            $search = strtolower(trim($request->search));

            $query->where(function ($q) use ($search) {
                $q->whereHas('item', fn ($i) =>
                    $i->where('name', 'like', "%{$search}%")
                )->orWhere('outgoing_destination', 'like', "%{$search}%");
            });
        }

        if ($request->filled('outgoing_destination')) {
            $query->where('outgoing_destination', trim(strtolower($request->outgoing_destination)));
        }

        $stockOuts = $query->latest()->paginate(10);

        return view('stock_outs.index', compact('stockOuts'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        return view('stock_outs.create', compact('items'));
    }

    public function store(Request $request)
    {
        // 🔥 NORMALISASI INPUT SEBELUM VALIDASI
        $request->merge([
            'outgoing_destination' => strtolower(trim($request->outgoing_destination)),
        ]);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'outgoing_destination' => [
                'required',
                Rule::in([
                    'penjualan',
                    'pemakaian_internal',
                    'peminjaman',
                    'rusak'
                ]),
            ],
            'description' => ['nullable', 'string'],
        ]);

        // ❌ LOGIKA TIDAK VALID
        if (
            $validated['outgoing_destination'] === 'rusak' &&
            str_contains(strtolower($validated['description'] ?? ''), 'pinjam')
        ) {
            return back()
                ->withErrors([
                    'outgoing_destination' => 'Barang rusak tidak bisa dipinjam.'
                ])
                ->withInput();
        }

        $item = Item::findOrFail($validated['item_id']);

        if ($validated['quantity'] > $item->stock) {
            return back()
                ->withErrors([
                    'quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $item->stock
                ])
                ->withInput();
        }

        $item->decrement('stock', $validated['quantity']);

        $stockOut = StockOut::create([
            'date' => $validated['date'],
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'outgoing_destination' => $validated['outgoing_destination'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah',
            'module' => 'Stock Out',
            'item_name' => $item->name,
            'details' => json_encode([
                'stock_out_id' => $stockOut->id,
                'item_id' => $validated['item_id'],
                'quantity' => $validated['quantity'],
                'destination' => $validated['outgoing_destination'],
            ]),
        ]);

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Stok keluar berhasil dicatat.');
    }

    public function edit(StockOut $stockOut)
    {
        $items = Item::orderBy('name')->get();
        return view('stock_outs.edit', compact('stockOut', 'items'));
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->merge([
            'outgoing_destination' => strtolower(trim($request->outgoing_destination)),
        ]);

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'outgoing_destination' => [
                'required',
                Rule::in([
                    'penjualan',
                    'pemakaian_internal',
                    'peminjaman',
                    'rusak'
                ]),
            ],
            'description' => ['nullable', 'string'],
        ]);

        if (
            $validated['outgoing_destination'] === 'rusak' &&
            str_contains(strtolower($validated['description'] ?? ''), 'pinjam')
        ) {
            return back()
                ->withErrors([
                    'outgoing_destination' => 'Barang rusak tidak bisa dipinjam.'
                ])
                ->withInput();
        }

        $oldItem = Item::findOrFail($stockOut->item_id);
        $newItem = Item::findOrFail($validated['item_id']);

        $oldItem->increment('stock', $stockOut->quantity);

        if ($validated['quantity'] > $newItem->stock) {
            $oldItem->decrement('stock', $stockOut->quantity);

            return back()
                ->withErrors([
                    'quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $newItem->stock
                ])
                ->withInput();
        }

        $newItem->decrement('stock', $validated['quantity']);

        $stockOut->update($validated);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit',
            'module' => 'Stock Out',
            'item_name' => $newItem->name,
            'details' => json_encode([
                'stock_out_id' => $stockOut->id,
                'item_id' => $validated['item_id'],
                'quantity' => $validated['quantity'],
                'destination' => $validated['outgoing_destination'],
            ]),
        ]);

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Data stok keluar berhasil diperbarui.');
    }

    public function destroy(StockOut $stockOut)
    {
        $item = Item::findOrFail($stockOut->item_id);
        $itemName = $item->name;
        $item->increment('stock', $stockOut->quantity);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus',
            'module' => 'Stock Out',
            'item_name' => $itemName,
            'details' => json_encode(['stock_out_id' => $stockOut->id]),
        ]);

        $stockOut->delete();

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Data stok keluar berhasil dihapus.');
    }
}
