<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;        
use App\Models\Peminjaman;  
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil data untuk user
        $items = Item::where('stock', '>', 0)->get();
        $myLoans = Peminjaman::with('item')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Mengarah ke file: resources/views/user/auth/dashboard.blade.php
        // Hapus '.auth', langsung ke folder user
return view('user.dashboard', compact('items', 'myLoans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tgl_kembali_max' => 'required|date',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => now(),
            'tgl_kembali_max' => $request->tgl_kembali_max,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pinjaman berhasil diajukan!');
    }

   public function kembalikan($id)
    {
        // Cari data yang ID-nya pas DAN punya user yang lagi login
        $pinjam = Peminjaman::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        // Pastikan cuma yang sudah disetujui yang bisa dikembalikan
        if ($pinjam->status == 'disetujui') {
            $pinjam->update([
                'status' => 'menunggu_kembali'
            ]);
            return back()->with('success', 'Permintaan pengembalian dikirim! Menunggu konfirmasi Admin.');
        }
        
        return back()->with('error', 'Status tidak valid untuk dikembalikan.');
    }
}