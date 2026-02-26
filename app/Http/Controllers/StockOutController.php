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
        
        // Fetch activity logs for Stock Out module
        $activityLogs = ActivityLog::where('module', 'Stock Out')
            ->with('user')
            ->latest()
            ->get();

        return view('stock_outs.index', compact('stockOuts', 'activityLogs'));
    }

    public function show(StockOut $stockOut)
    {
        // Redirect to edit or index - no dedicated show view
        return redirect()->route('stock-outs.index');
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

        // Conditional validation for return_date (required if peminjaman)
        $rules = [
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'borrower_name' => ['required_if:outgoing_destination,peminjaman', 'nullable', 'string', 'max:255'],
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
        ];

        // Add return_date validation if destination is peminjaman
        if ($request->outgoing_destination === 'peminjaman') {
            $rules['return_date'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['return_date'] = ['nullable'];
        }

        $validated = $request->validate($rules);

        // Check borrowing limit for peminjaman destination (max 2 unreturned items of same product per day)
        if ($validated['outgoing_destination'] === 'peminjaman') {
            $unreturnedBorrows = StockOut::where('item_id', $validated['item_id'])
                ->where('is_borrowed', true)
                ->whereNull('returned_at')
                ->count();

            if ($unreturnedBorrows >= 2) {
                return back()
                    ->withErrors([
                        'item_id' => 'Barang ini sudah dipinjam sebanyak 2 kali dan belum dikembalikan. Maksimal peminjaman adalah 2 barang per hari. Tunggu hingga salah satu barang dikembalikan.'
                    ])
                    ->withInput();
            }
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
            'date' => now(),
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'outgoing_destination' => $validated['outgoing_destination'],
            'borrower_name' => $validated['outgoing_destination'] === 'peminjaman' ? ($request->borrower_name ?? null) : null,
            'description' => $validated['description'],
            'user_id' => Auth::id(),
            'is_borrowed' => $validated['outgoing_destination'] === 'peminjaman',
            'return_date' => $validated['outgoing_destination'] === 'peminjaman' ? $validated['return_date'] : null,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah',
            'module' => 'Stock Out',
            'item_name' => $item->name,
            'details' => json_encode([
                'stock_out_id' => $stockOut->id,
                'borrower_name' => $stockOut->borrower_name, // ✨ TAMBAHKAN INI
                'item_id' => $validated['item_id'],
                'item_name' => $item->name,
                'date' => now()->format('Y-m-d H:i:s'),
                'quantity' => $validated['quantity'],
                'outgoing_destination' => $validated['outgoing_destination'],
                'description' => $validated['description'],
                'is_borrowed' => $validated['outgoing_destination'] === 'peminjaman',
                'return_date' => $validated['outgoing_destination'] === 'peminjaman' ? $validated['return_date'] : null,
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
        // Prevent editing if borrowed item is already returned
        if ($stockOut->is_borrowed && $stockOut->returned_at) {
            return back()
                ->withErrors([
                    'general' => 'Transaksi peminjaman ini tidak dapat diubah karena barang sudah dikembalikan.'
                ])
                ->withInput();
        }

        $request->merge([
            'outgoing_destination' => strtolower(trim($request->outgoing_destination)),
        ]);

        // Conditional validation for return_date (required if peminjaman)
        $rules = [
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'borrower_name' => ['required_if:outgoing_destination,peminjaman', 'nullable', 'string', 'max:255'], // ✨ TAMBAHKAN INI
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
        ];

        // Add return_date validation if destination is peminjaman
        if ($request->outgoing_destination === 'peminjaman') {
            $rules['return_date'] = ['required', 'date', 'after_or_equal:today'];
        } else {
            $rules['return_date'] = ['nullable'];
        }

        $validated = $request->validate($rules);

        // Check borrowing limit for peminjaman destination (max 2 unreturned items of same product per day)
        // Exclude current record from the count
        if ($validated['outgoing_destination'] === 'peminjaman') {
            $unreturnedBorrows = StockOut::where('item_id', $validated['item_id'])
                ->where('is_borrowed', true)
                ->whereNull('returned_at')
                ->where('id', '!=', $stockOut->id)  // Exclude current record
                ->count();

            if ($unreturnedBorrows >= 2) {
                return back()
                    ->withErrors([
                        'item_id' => 'Barang ini sudah dipinjam sebanyak 2 kali dan belum dikembalikan. Maksimal peminjaman adalah 2 barang per hari. Tunggu hingga salah satu barang dikembalikan.'
                    ])
                    ->withInput();
            }
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

        // Capture original values for comparison
        $originalData = $stockOut->getOriginal();

        $updateData = [
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'outgoing_destination' => $validated['outgoing_destination'],
            'borrower_name' => $validated['outgoing_destination'] === 'peminjaman' ? ($request->borrower_name ?? null) : null, // ✨ TAMBAHKAN INI
            'description' => $validated['description'],
            'is_borrowed' => $validated['outgoing_destination'] === 'peminjaman',
            'return_date' => $validated['outgoing_destination'] === 'peminjaman' ? $validated['return_date'] : null,
        ];

        $stockOut->update($updateData);

        // Track changes
        $changes = [];
    $fieldsToCheck = ['quantity', 'outgoing_destination', 'description', 'is_borrowed', 'return_date', 'borrower_name']; // ✨ TAMBAHKAN 'borrower_name'
        foreach ($fieldsToCheck as $field) {
            $oldValue = $originalData[$field] ?? null;
            $newValue = $updateData[$field] ?? null;
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
            'module' => 'Stock Out',
            'item_name' => $newItem->name,
            'details' => json_encode([
                'stock_out_id' => $stockOut->id,
                'item_id' => $validated['item_id'],
                'changes' => $changes,
            ]),
        ]);

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Data stok keluar berhasil diperbarui.');
    }

    public function destroy(StockOut $stockOut)
    {
        // Prevent deletion if borrowed item is already returned
        if ($stockOut->is_borrowed && $stockOut->returned_at) {
            return back()
                ->withErrors([
                    'general' => 'Transaksi peminjaman ini tidak dapat dihapus karena barang sudah dikembalikan.'
                ]);
        }

        $item = Item::findOrFail($stockOut->item_id);
        $itemName = $item->name;
        $item->increment('stock', $stockOut->quantity);

        // Capture data before deletion
        $stockOutData = [
            'stock_out_id' => $stockOut->id,
            'item_name' => $itemName,
            'date' => $stockOut->date,
            'quantity' => $stockOut->quantity,
            'outgoing_destination' => $stockOut->outgoing_destination,
            'description' => $stockOut->description,
        ];

        // Log activity before deletion
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus',
            'module' => 'Stock Out',
            'item_name' => $itemName,
            'details' => json_encode($stockOutData),
        ]);

        $stockOut->delete();

        return redirect()
            ->route('stock-outs.index')
            ->with('success', 'Data stok keluar berhasil dihapus.');
    }

    /**
     * Mark a borrowed item as returned
     */
    public function markReturned(StockOut $stockOut)
    {
        // Check if this is actually a borrowed item
        if (!$stockOut->is_borrowed || $stockOut->returned_at) {
            return response()->json([
                'success' => false,
                'message' => 'Item ini tidak bisa ditandai sebagai dikembalikan.'
            ], 400);
        }

        try {
            $item = $stockOut->item;
            
            // Mark as returned
            $stockOut->update(['returned_at' => now()]);
            
            // Restore stock
            $item->increment('stock', $stockOut->quantity);

            // Log activity
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Kembalikan',
                'module' => 'Stock Out',
                'item_name' => $item->name,
                'details' => json_encode([
                    'stock_out_id' => $stockOut->id,
                    'item_id' => $stockOut->item_id,
                    'item_name' => $item->name,
                    'quantity' => $stockOut->quantity,
                    'returned_at' => now()->format('Y-m-d H:i:s'),
                ]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil ditandai sebagai dikembalikan dan stok dipulihkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current borrow count for an item
     * API endpoint to check how many of the same item are currently borrowed
     */
    public function getBorrowCount($itemId)
    {
        $currentCount = StockOut::where('item_id', $itemId)
            ->where('is_borrowed', true)
            ->whereNull('returned_at')
            ->count();

        $canBorrow = $currentCount < 2;
        $remaining = max(0, 2 - $currentCount);

        return response()->json([
            'currentCount' => $currentCount,
            'canBorrow' => $canBorrow,
            'remaining' => $remaining,
            'maxBorrow' => 2
        ]);
    }
}
