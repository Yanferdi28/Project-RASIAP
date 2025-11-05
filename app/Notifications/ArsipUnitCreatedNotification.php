<?php

namespace App\Notifications;

use App\Models\ArsipUnit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ArsipUnitCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected ArsipUnit $arsipUnit
    ) {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verifikasi Arsip Unit Baru Diperlukan')
            ->line('Arsip unit baru telah dibuat dan memerlukan verifikasi.')
            ->line('ID Berkas: ' . $this->arsipUnit->id_berkas)
            ->line('Uraian: ' . $this->arsipUnit->uraian_informasi)
            ->action('Verifikasi Arsip', url('/admin/arsip-units/' . $this->arsipUnit->id_berkas . '/edit'))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'arsip_unit_id' => $this->arsipUnit->id_berkas,
            'title' => 'Verifikasi Arsip Unit Baru Diperlukan',
            'message' => 'Arsip unit dengan ID ' . $this->arsipUnit->id_berkas . ' memerlukan verifikasi oleh operator.',
            'link' => '/admin/arsip-units/' . $this->arsipUnit->id_berkas . '/edit',
        ];
    }
}
