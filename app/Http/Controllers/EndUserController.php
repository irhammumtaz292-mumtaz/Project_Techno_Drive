<?php

namespace App\Http\Controllers;

use App\Models\EndUser;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    public function index()
    {
        $endUsers = EndUser::latest()->paginate(10);
        return view('end_users.index', compact('endUsers'));
    }

    public function create()
    {
        return view('end_users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
        ]);

        EndUser::create($request->except('_token'));

        return redirect()->route('end_users.index')->with('success', 'Data End User berhasil ditambahkan.');
    }

    public function edit(EndUser $endUser)
    {
        return view('end_users.edit', compact('endUser'));
    }

    public function update(Request $request, EndUser $endUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
        ]);

        $endUser->update($request->except('_token'));

        return redirect()->route('end_users.index')->with('success', 'Data End User berhasil diperbarui.');
    }

    public function destroy(EndUser $endUser)
    {
        $endUser->delete();
        return redirect()->route('end_users.index')->with('success', 'Data End User berhasil dihapus.');
    }
}