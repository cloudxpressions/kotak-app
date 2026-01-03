<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DatabaseBackupNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $backupFile;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $backupFile = null)
    {
        $this->message = $message;
        $this->backupFile = $backupFile;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'backup_file' => $this->backupFile,
            'type' => 'database_backup',
        ];
    }
}