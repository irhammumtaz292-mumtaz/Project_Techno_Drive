<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Parameter ...$roles memungkinkan kita memasukkan lebih dari 1 role
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login dan role-nya ada di daftar yang diizinkan
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Jika tidak punya akses, tampilkan halaman 403 Forbidden
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}