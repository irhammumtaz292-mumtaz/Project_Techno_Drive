<?php

namespace App\Http\Controllers;

use App\Models\TestingTool;
use App\Models\TestEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    /**
     * Menampilkan daftar alat uji (TestingTool)
     */
    public function indexTool()
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['supervisor', 'director', 'direktur', 'logistics', 'logistik']), 403, 'Akses ditolak.');

        $equipments = TestEquipment::latest()->paginate(10);
        return view('equipments.index', compact('equipments'));
    }

    /**
     * Form tambah alat uji
     */
    public function createTool()
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang berhak menambah alat.');

        return view('equipments.create');
    }

    /**
     * Menyimpan data alat uji baru khusus role Logistik
     * Validasi wajib: name, brand_model, unit_count
     */
    public function storeTool(Request $request)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang berhak menambah alat.');

        $request->validate([
            'name'        => 'required|string|max:255',
            'brand_model' => 'required|string|max:255',
            'unit_count'  => 'required|integer|min:1',
            'status'      => 'nullable|string',
        ], [
            'name.required'        => 'Nama & Jenis Alat wajib diisi.',
            'brand_model.required' => 'Merek & Model wajib diisi.',
            'unit_count.required'  => 'Jumlah Unit wajib diisi.',
            'unit_count.min'       => 'Jumlah Unit minimal 1.',
        ]);

        $statusInput = $request->status ? strtolower(trim($request->status)) : 'tersedia';
        $normalizedStatus = in_array($statusInput, ['sedang dipinjam', 'sedang_dipinjam']) ? 'sedang_dipinjam' : 'tersedia';
        $statusDb = ($normalizedStatus === 'sedang_dipinjam') ? 'Sedang Dipinjam' : 'Tersedia';

        // 1. Simpan ke tabel testing_tools
        $tool = TestingTool::create([
            'name'        => $request->name,
            'brand_model' => $request->brand_model,
            'unit_count'  => $request->unit_count,
            'status'      => $normalizedStatus,
        ]);

        // 2. Sinkronkan ke tabel test_equipments
        TestEquipment::create([
            'name'          => $request->name,
            'brand_model'   => $request->brand_model,
            'unit_count'    => $request->unit_count,
            'serial_number' => $request->brand_model . '-' . str_pad($tool->id, 3, '0', STR_PAD_LEFT),
            'status'        => $statusDb,
        ]);

        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil ditambahkan.');
    }

    /**
     * Ubah status ketersediaan alat (Tersedia / Sedang Dipinjam)
     */
    public function toggleStatus($id)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang berhak mengubah status.');

        // Cek TestEquipment atau TestingTool
        $equipment = TestEquipment::find($id);
        if ($equipment) {
            $newStatus = ($equipment->status === 'Tersedia') ? 'Sedang Dipinjam' : 'Tersedia';
            $equipment->update(['status' => $newStatus]);

            $tool = TestingTool::where('name', $equipment->name)->first();
            if ($tool) {
                $tool->update(['status' => ($newStatus === 'Tersedia') ? 'tersedia' : 'sedang_dipinjam']);
            }

            return back()->with('success', 'Status ketersediaan alat "' . $equipment->name . '" berhasil diubah menjadi ' . $newStatus . '.');
        }

        $tool = TestingTool::find($id);
        if ($tool) {
            $newStatus = ($tool->status === 'tersedia') ? 'sedang_dipinjam' : 'tersedia';
            $tool->update(['status' => $newStatus]);
            return back()->with('success', 'Status ketersediaan alat berhasil diubah.');
        }

        return back()->with('error', 'Data alat tidak ditemukan.');
    }
}
