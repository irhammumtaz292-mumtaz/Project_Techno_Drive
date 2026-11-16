<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = User::latest()->paginate(10);
        return view('staffs.index', compact('staffs'));
    }

    public function create()
    {
        return view('staffs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:Supervisor,Director,Staff,Finance,Logistics',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('staffs.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $staff)
    {
        return view('staffs.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'role'  => 'required|in:Supervisor,Director,Staff,Finance,Logistics',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Perbarui password hanya jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $staff->update($data);

        return redirect()->route('staffs.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function destroy(User $staff)
{
    // Mencegah Supervisor menghapus akunnya sendiri
    if ($staff->id === Auth::id()) {
        return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
    }

    $staff->delete();
    return redirect()->route('staffs.index')->with('success', 'Akun berhasil dihapus.');
}
}