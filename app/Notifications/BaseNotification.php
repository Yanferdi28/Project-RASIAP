<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Base notification class untuk semua database notifications
 * Menyediakan struktur dasar dan helper methods
 */
abstract class BaseNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Template method untuk toDatabase implementation
     * Child classes harus override buildNotification()
     */
    final public function toDatabase(object $notifiable): array
    {
        return $this->buildNotification()->getDatabaseMessage();
    }

    /**
     * Child classes harus implement method ini untuk membangun notifikasi
     */
    abstract protected function buildNotification(): \Filament\Notifications\Notification;
}
