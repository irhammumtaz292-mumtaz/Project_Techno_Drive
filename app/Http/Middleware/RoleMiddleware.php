<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Parameter ...$roles memungkinkan kita memasukkan lebih dari 1 role
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        // Normalisasi role pengguna (lowercase + trim)
        $userRole = strtolower(trim(Auth::user()->role ?? ''));

        // Alias & sinonim role (mendukung 'direktur' & 'director', 'logistik' & 'logistics', 'keuangan' & 'finance')
        $userAliases = [$userRole];
        if (in_array($userRole, ['director', 'direktur'])) {
            $userAliases = ['director', 'direktur'];
        } elseif (in_array($userRole, ['logistics', 'logistik'])) {
            $userAliases = ['logistics', 'logistik'];
        } elseif (in_array($userRole, ['finance', 'keuangan'])) {
            $userAliases = ['finance', 'keuangan'];
        }

        // Parse dan normalisasi semua role yang diizinkan
        $allowedRoles = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $subRole) {
                $subNorm = strtolower(trim($subRole));
                $allowedRoles[] = $subNorm;

                // Daftarkan sinonim ke dalam daftar allowed
                if ($subNorm === 'director' || $subNorm === 'direktur') {
                    $allowedRoles[] = 'director';
                    $allowedRoles[] = 'direktur';
                } elseif ($subNorm === 'logistics' || $subNorm === 'logistik') {
                    $allowedRoles[] = 'logistics';
                    $allowedRoles[] = 'logistik';
                } elseif ($subNorm === 'finance' || $subNorm === 'keuangan') {
                    $allowedRoles[] = 'finance';
                    $allowedRoles[] = 'keuangan';
                }
            }
        }

        // Cek apakah ada alias role user yang cocok dengan allowedRoles
        $hasAccess = false;
        foreach ($userAliases as $alias) {
            if (in_array($alias, $allowedRoles, true)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
