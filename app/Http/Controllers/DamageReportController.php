<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    /* =========================
       USER (LAPOR KERUSAKAN)
       ========================= */

    // tampilkan form laporan (USER)
    public function create()
    {
        // WAJIB: file ada di resources/views/damage_reports/create.blade.php
        return view('damage_reports.create');
    }

    // simpan laporan (USER)
    public function store(Request $request)
    {
        $request->validate([
            'description'   => 'required|string',
            'condition'     => 'required|in:baik,rusak_ringan,rusak_berat',
            'photo_report'  => 'required|image|max:2048',
        ]);

        $photoPath = $request->file('photo_report')
            ->store('damage_reports', 'public');

        DamageReport::create([
            'user_id'      => Auth::id(),
            'description'  => $request->description,
            'condition'    => $request->condition,
            'photo_report' => $photoPath,
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'Kondisi barang berhasil dikirim');
    }

    /* =========================
       ADMIN (KELOLA LAPORAN)
       ========================= */

    // list semua laporan (ADMIN) - Cek Kondisi Barang
    public function index(Request $request)
    {
        $query = DamageReport::with(['user', 'item']);
        $search = null;
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%");
            })->orWhereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('description', 'like', "%{$search}%");
        }
        
        $reports = $query->latest()->paginate(10);
        $items = Item::orderBy('name')->get();
        return view('damage_reports.index', compact('reports', 'items', 'search'));
    }

    // simpan laporan dari admin
    public function storeFromAdmin(Request $request)
    {
        // Validasi: foto wajib jika kondisi rusak ringan atau rusak berat
        $photoRequired = in_array($request->condition, ['rusak_ringan', 'rusak_berat']);
        
        $request->validate([
            'item_id'       => 'nullable|exists:items,id',
            'condition'     => 'required|in:baik,rusak_ringan,rusak_berat',
            'description'   => 'required|string',
            'photo_report'  => $photoRequired ? 'required|image|max:2048' : 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo_report')) {
            $photoPath = $request->file('photo_report')->store('damage_reports', 'public');
        }

        DamageReport::create([
            'user_id'      => Auth::id(),
            'item_id'      => $request->item_id,
            'location'     => 'Admin Report',
            'condition'    => $request->condition,
            'description'  => $request->description,
            'photo_report' => $photoPath,
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'Kondisi barang berhasil ditambahkan');
    }

    // update kondisi dan deskripsi
    public function update(Request $request, $id)
    {
        // Validasi: foto wajib jika kondisi rusak ringan atau rusak berat
        $photoRequired = in_array($request->condition, ['rusak_ringan', 'rusak_berat']);
        
        $request->validate([
            'condition'    => 'required|in:baik,rusak_ringan,rusak_berat',
            'description'  => 'required|string',
            'photo_report' => $photoRequired ? 'required|image|max:2048' : 'nullable|image|max:2048',
        ]);

        $report = DamageReport::findOrFail($id);

        $updateData = [
            'condition'   => $request->condition,
            'description' => $request->description,
        ];

        // Upload foto jika ada
        if ($request->hasFile('photo_report')) {
            $photoPath = $request->file('photo_report')->store('damage_reports', 'public');
            $updateData['photo_report'] = $photoPath;
        }

        $report->update($updateData);

        return back()->with('success', 'Kondisi barang berhasil diperbarui');
    }

    // hapus laporan
    public function destroy($id)
    {
        $report = DamageReport::findOrFail($id);
        $report->delete();

        return back()->with('success', 'Kondisi barang berhasil dihapus');
    }

    // ubah status ke PROCESS
    public function process($id)
    {
        $report = DamageReport::findOrFail($id);

        $report->update([
            'status' => 'process'
        ]);

        return back()->with('success', 'Laporan sedang diproses');
    }

    // selesaikan laporan
    public function done(Request $request, $id)
    {
        $request->validate([
            'photo_result' => 'required|image|max:2048',
            'admin_note'   => 'nullable|string',
        ]);

        $report = DamageReport::findOrFail($id);

        $photoResult = $request->file('photo_result')
            ->store('damage_reports', 'public');

        $report->update([
            'status'       => 'done',
            'photo_result' => $photoResult,
            'admin_note'   => $request->admin_note,
        ]);

        return back()->with('success', 'Laporan selesai');
    }
}
