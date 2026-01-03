<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Deletion Request Rejected')
            ->line('Your request for account deletion has been rejected by the administrator.')
            ->line('Please contact support if you have any questions.')
            ->action('Login to Dashboard', url('/admin/login'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Deletion Request Rejected',
            'message' => 'Your account deletion request was rejected.',
            'action_url' => route('admin.profile.edit'),
        ];
    }
}
