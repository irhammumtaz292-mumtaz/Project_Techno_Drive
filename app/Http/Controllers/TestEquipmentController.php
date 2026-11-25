<?php

namespace App\Http\Controllers;

use App\Models\TestEquipment;
use App\Models\TestingTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestEquipmentController extends Controller
{
    // READ — Logistics, Supervisor, Director/Direktur dapat melihat daftar
    public function index()
    {
        $equipments = TestEquipment::latest()->paginate(10);
        return view('equipments.index', compact('equipments'));
    }

    public function show(TestEquipment $equipment)
    {
        return view('equipments.show', compact('equipment'));
    }

    // WRITE — Hanya Logistics
    public function create()
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang dapat menambah alat.');
        return view('equipments.create');
    }

    // Alias storeTool
    public function storeTool(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang dapat menambah alat.');

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

        // 2. Simpan ke tabel test_equipments
        TestEquipment::create([
            'name'          => $request->name,
            'brand_model'   => $request->brand_model,
            'unit_count'    => $request->unit_count,
            'serial_number' => $request->serial_number ?? ($request->brand_model . '-' . str_pad($tool->id, 3, '0', STR_PAD_LEFT)),
            'status'        => $statusDb,
        ]);

        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil ditambahkan.');
    }

    public function edit(TestEquipment $equipment)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang dapat mengedit alat.');
        return view('equipments.edit', compact('equipment'));
    }

    public function update(Request $request, TestEquipment $equipment)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang dapat mengedit alat.');

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

        $equipment->update([
            'name'        => $request->name,
            'brand_model' => $request->brand_model,
            'unit_count'  => $request->unit_count,
            'status'      => $statusDb,
        ]);

        $tool = TestingTool::where('name', $equipment->name)->first();
        if ($tool) {
            $tool->update([
                'name'        => $request->name,
                'brand_model' => $request->brand_model,
                'unit_count'  => $request->unit_count,
                'status'      => $normalizedStatus,
            ]);
        }

        return redirect()->route('equipments.index')->with('success', 'Data alat uji berhasil diperbarui.');
    }

    public function destroy(TestEquipment $equipment)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak.');
        
        TestingTool::where('name', $equipment->name)->delete();
        $equipment->delete();

        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil dihapus.');
    }

    // TOGGLE STATUS — Hanya Logistics (verifikator ketersediaan)
    public function toggleStatus(TestEquipment $equipment)
    {
        $userRole = strtolower(trim(Auth::user()->role ?? ''));
        abort_unless(in_array($userRole, ['logistics', 'logistik']), 403, 'Akses ditolak. Hanya Logistik yang dapat mengubah status alat.');

        $newStatus = ($equipment->status === 'Tersedia') ? 'Sedang Dipinjam' : 'Tersedia';
        $equipment->update(['status' => $newStatus]);

        $tool = TestingTool::where('name', $equipment->name)->first();
        if ($tool) {
            $tool->update(['status' => ($newStatus === 'Tersedia') ? 'tersedia' : 'sedang_dipinjam']);
        }

        return back()->with('success', 'Status alat "' . $equipment->name . '" berhasil diubah menjadi ' . $newStatus . '.');
    }
}
