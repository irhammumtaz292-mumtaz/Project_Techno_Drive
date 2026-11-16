<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Data Statistik (disinkronkan dengan data di Kelola Proyek)
        $totalProyekBerjalan = Project::where('status_pekerjaan', 'On Progress')->count();
        $pendingReports = Project::where('status_pekerjaan', 'On Progress')
            ->whereDate('deadline_laporan', '<', $today)
            ->count();
        $waitingPayment = Project::where('status_pembayaran', 'Waiting Payment')->count();
        $paid = Project::where('status_pembayaran', 'Paid')->count();

        // 2. Data Khusus Staff
        $tugasSaya = 0;
        $laporanMendekatiDeadline = 0;
        if ($user && $user->role === 'Staff') {
            $tugasSaya = $user->projects()
                ->where('status_pembayaran', 'Paid')
                ->where('status_pekerjaan', 'On Progress')
                ->count();

            $laporanMendekatiDeadline = $user->projects()
                ->where('status_pembayaran', 'Paid')
                ->where('status_pekerjaan', 'On Progress')
                ->whereDate('deadline_laporan', '<=', $today->copy()->addDays(3))
                ->count();
        }

        // 3. Aktivitas Terbaru (disinkronkan dengan data di Kelola Proyek)
        if ($user && $user->role === 'Staff') {
            $recentProjects = $user->projects()
                ->with(['customer', 'endUser', 'po'])
                ->where('status_pembayaran', 'Paid')
                ->latest('projects.created_at')
                ->take(5)
                ->get();
        } else {
            $recentProjects = Project::with(['customer', 'endUser', 'po'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'user',
            'totalProyekBerjalan',
            'pendingReports',
            'waitingPayment',
            'paid',
            'tugasSaya',
            'laporanMendekatiDeadline',
            'recentProjects'
        ));
    }
}
