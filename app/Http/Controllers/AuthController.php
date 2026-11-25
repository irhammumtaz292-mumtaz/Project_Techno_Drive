<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        // Jika sudah login, langsung arahkan sesuai role
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        // Mendukung input dari name="email" maupun name="username"
        $loginInput = trim($request->input('email') ?? $request->input('username') ?? '');

        // Merge ke request agar validasi & old() selalu konsisten
        $request->merge([
            'email'       => $loginInput,
            'username'    => $loginInput,
            'login_field' => $loginInput,
        ]);

        // Validasi input
        $request->validate([
            'login_field' => ['required'],
            'password'    => ['required'],
        ], [
            'login_field.required' => 'Email atau username wajib diisi.',
            'password.required'    => 'Password wajib diisi.',
        ]);

        // Tentukan apakah user memasukkan format email atau username/nama
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $request->password,
        ];

        // Coba melakukan login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate(); // Mencegah serangan session fixation
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Jika gagal, kembalikan ke halaman login beserta pesan error
        return back()->withErrors([
            'email' => 'Email/Username atau password yang Anda masukkan salah.',
        ])->onlyInput('email', 'username');
    }

    // Redirect otomatis setelah login berdasarkan role
    protected function redirectBasedOnRole($user)
    {
        $role = strtolower(trim($user->role ?? ''));

        // Role Direktur diarahkan langsung ke daftar proyek (projects.index)
        if (in_array($role, ['direktur', 'director'])) {
            return redirect()->intended(route('projects.index'));
        }

        // Role Logistik diarahkan ke menu kelola alat uji (equipments.index)
        if (in_array($role, ['logistik', 'logistics'])) {
            return redirect()->intended(route('equipments.index'));
        }

        // Role Keuangan diarahkan ke verifikasi pembayaran
        if (in_array($role, ['keuangan', 'finance'])) {
            return redirect()->intended(route('payment.index'));
        }

        // Default: Dashboard
        return redirect()->intended(route('dashboard'));
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
