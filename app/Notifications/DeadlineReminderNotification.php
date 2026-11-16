<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Project $project,
        public readonly string $label // Contoh: "H-3 (3 hari lagi)"
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
            'type'        => 'deadline_reminder',
            'project_id'  => $this->project->id,
            'project_name'=> $this->project->name,
            'deadline'    => $this->project->deadline_laporan,
            'label'       => $this->label,
            'message'     => "⏰ Reminder {$this->label}: Deadline laporan proyek \"{$this->project->name}\" pada "
                             . \Carbon\Carbon::parse($this->project->deadline_laporan)->format('d M Y') . ".",
            'url'         => route('projects.show', $this->project->id),
        ];
    }

    // Email yang dikirim ke Staff
    public function toMail(object $notifiable): MailMessage
    {
        $deadline = \Carbon\Carbon::parse($this->project->deadline_laporan)->format('d M Y');

        return (new MailMessage)
            ->subject("⏰ Reminder {$this->label} — Deadline Laporan Proyek")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Ini adalah pengingat bahwa deadline laporan untuk proyek berikut semakin dekat:")
            ->line("**Proyek:** {$this->project->name}")
            ->line("**Deadline:** {$deadline}")
            ->line("**Status:** {$this->label}")
            ->action('Lihat Detail Proyek', route('projects.show', $this->project->id))
            ->line('Harap segera upload Final Report sebelum batas waktu.');
    }
}
