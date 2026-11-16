<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Menampilkan daftar customer
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    // Menampilkan form tambah customer
    public function create()
    {
        return view('customers.create');
    }

    // Menyimpan data customer baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
        ]);

        Customer::create($request->only(['name', 'address', 'contact_person']));

        return redirect()->route('customers.index')->with('success', 'Data Customer berhasil ditambahkan.');
    }

    // Menampilkan form edit customer
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    // Memperbarui data customer
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
        ]);

        $customer->update($request->only(['name', 'address', 'contact_person']));

        return redirect()->route('customers.index')->with('success', 'Data Customer berhasil diperbarui.');
    }

    // Menghapus data customer
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Data Customer berhasil dihapus.');
    }
}