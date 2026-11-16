<?php

namespace App\Http\Controllers;

use App\Models\TestEquipment;
use Illuminate\Http\Request;

class TestEquipmentController extends Controller
{
    public function index()
    {
        $equipments = TestEquipment::latest()->paginate(10);
        return view('equipments.index', compact('equipments'));
    }

    public function create()
    {
        return view('equipments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:test_equipments,serial_number|max:100',
            'status' => 'required|in:Tersedia,Sedang Dipinjam',
        ]);

        TestEquipment::create($request->except('_token'));

        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil ditambahkan.');
    }

    public function edit(TestEquipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    public function update(Request $request, TestEquipment $equipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:100|unique:test_equipments,serial_number,' . $equipment->id,
            'status' => 'required|in:Tersedia,Sedang Dipinjam',
        ]);

        $equipment->update($request->except('_token'));

        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil diperbarui.');
    }

    public function destroy(TestEquipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipments.index')->with('success', 'Alat uji berhasil dihapus.');
    }
}