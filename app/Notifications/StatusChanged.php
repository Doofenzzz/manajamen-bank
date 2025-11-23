<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public string $tipe,
        public int $pengajuanId,
        public string $status,
        public ?string $reason = null,
        public ?string $updatedAt = null, // boleh null, fallback now()
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // render view HTML kamu: resources/views/emails/status_pengajuan.blade.php
        return (new MailMessage)
            ->subject("Status Pengajuan {$this->tipe} #{$this->pengajuanId}: " . ucfirst($this->status))
            ->view('emails.status_pengajuan', [
                'name'      => $notifiable->name,
                'tipe'      => $this->tipe,
                'id'        => $this->pengajuanId,
                'status'    => $this->status,     // 'pending' | 'diterima' | 'ditolak'
                'reason'    => $this->reason,     // opsional
                'updatedAt' => $this->updatedAt ?? now(),
            ]);
    }
}
