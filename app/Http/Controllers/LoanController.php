<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $items = Item::all(); // Barang yang bisa dipilih
        $myLoans = Peminjaman::where('user_id', Auth::id())->latest()->get(); // Riwayat user login
        return view('user.dashboard', compact('items', 'myLoans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'tgl_kembali_max' => 'required|date|after:today',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(), // OTOMATIS ambil ID yang login
            'item_id' => $request->item_id,
            'jumlah' => $request->jumlah,
            'tgl_pinjam' => now(),
            'tgl_kembali_max' => $request->tgl_kembali_max,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan pinjam berhasil dikirim!');
    }
}