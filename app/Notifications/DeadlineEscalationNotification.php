<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DeadlineEscalationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Project $project,
        public readonly int $hariTerlambat
    ) {}

    // Kirim via database DAN email
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    // Notifikasi yang disimpan ke tabel notifications
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'          => 'deadline_escalation',
            'project_id'    => $this->project->id,
            'project_name'  => $this->project->name,
            'deadline'      => $this->project->deadline_laporan,
            'hari_terlambat'=> $this->hariTerlambat,
            'message'       => "🚨 ESKALASI: Proyek \"{$this->project->name}\" telah MELEWATI tenggat laporan "
                               . "{$this->hariTerlambat} hari! Segera tindak lanjuti.",
            'url'           => route('projects.show', $this->project->id),
        ];
    }

    // Email eskalasi ke Supervisor & Direktur
    public function toMail(object $notifiable): MailMessage
    {
        $deadline = \Carbon\Carbon::parse($this->project->deadline_laporan)->format('d M Y');

        return (new MailMessage)
            ->subject("🚨 ESKALASI — Proyek \"{$this->project->name}\" Terlambat {$this->hariTerlambat} Hari")
            ->greeting("Perhatian, {$notifiable->name}!")
            ->line("Proyek berikut telah **melewati tenggat** pengumpulan laporan akhir:")
            ->line("**Proyek:** {$this->project->name}")
            ->line("**Deadline Asli:** {$deadline}")
            ->line("**Keterlambatan:** {$this->hariTerlambat} hari")
            ->action('Lihat Detail Proyek', route('projects.show', $this->project->id))
            ->line('Mohon segera ditindaklanjuti.');
    }
}
