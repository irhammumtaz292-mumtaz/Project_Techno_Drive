<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as BaseNotification;
use Carbon\Carbon;
use App\Models\Project;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ============================================================
// SCHEDULER: Reminder & Eskalasi Deadline Laporan
// Dijalankan setiap hari jam 08:00 pagi
// ============================================================
Schedule::call(function () {
    $today = Carbon::today();

    // Ambil proyek yang belum selesai beserta relasi staff, customer, dan endUser
    $projects = Project::with(['staff', 'customer', 'endUser'])
        ->where('status_pekerjaan', 'On Progress')
        ->get();

    foreach ($projects as $project) {
        $deadline  = Carbon::parse($project->deadline_laporan);
        $diffDays  = (int) $today->diffInDays($deadline, false); // Negatif jika sudah lewat

        // ----------------------------------------------------------------
        // REMINDER ke Staff: H-3, H-1, dan Hari-H
        // ----------------------------------------------------------------
        if (in_array($diffDays, [3, 1, 0])) {
            $label = match ($diffDays) {
                3 => 'H-3 (3 hari lagi)',
                1 => 'H-1 (besok)',
                0 => 'HARI INI',
            };

            foreach ($project->staff as $staff) {
                // Simpan notifikasi ke tabel database_notifications
                $staff->notify(
                    new \App\Notifications\DeadlineReminderNotification($project, $label)
                );
            }
        }

        // ----------------------------------------------------------------
        // ESKALASI ke Supervisor & Direktur: jika lewat tenggat
        // ----------------------------------------------------------------
        if ($diffDays < 0) {
            $hariTerlambat = abs($diffDays);

            // Ambil semua Supervisor & Director
            $escalationTargets = User::whereIn('role', ['Supervisor', 'Director'])->get();

            foreach ($escalationTargets as $target) {
                $target->notify(
                    new \App\Notifications\DeadlineEscalationNotification($project, $hariTerlambat)
                );
            }
        }
    }
})->dailyAt('08:00'); // Dijalankan setiap jam 8 pagi