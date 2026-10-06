<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivityNotification extends Notification
{
    use Queueable;
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database']; // can add 'mail' or 'broadcast'
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'time'    => now()->toDateTimeString(),
        ];
    }
}