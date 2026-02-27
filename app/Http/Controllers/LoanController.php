<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Peminjaman; // Menggunakan model Peminjaman untuk konsistensi
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserLoanController extends Controller
{
    public function index()
    {
        // Ambil data untuk form & tabel
        $items = Item::where('stock', '>', 0)->get();
        $myLoans = Peminjaman::with('item')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.dashboard', compact('items', 'myLoans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'jumlah' => 'required|integer|min:1',
            'tgl_kembali_max' => 'required|date|after_or_equal:today',
        ]);

        $item = Item::findOrFail($request->item_id);
        
        if ($item->stock < $request->jumlah) {
            return back()->with('error', 'Stok tidak cukup!');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => now(),
            'tgl_kembali_max' => $request->tgl_kembali_max,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permohonan pinjaman berhasil dikirim!');
    }

    public function kembalikan($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $loan = Peminjaman::where('id', $id)
                    ->where('user_id', Auth::id())
                    ->whereIn('status', ['disetujui', 'dipinjam']) // Hanya bisa mengembalikan barang yang statusnya disetujui/dipinjam
                    ->lockForUpdate() // Mencegah race condition
                    ->firstOrFail();

                // 1. Update status peminjaman menjadi 'kembali'
                $loan->update(['status' => 'kembali']);

                // 2. Kembalikan stok barang
                $item = Item::findOrFail($loan->item_id);
                $item->increment('stock', $loan->jumlah);
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }

        return back()->with('success', 'Barang berhasil dikembalikan dan stok telah diperbarui!');
    }
}