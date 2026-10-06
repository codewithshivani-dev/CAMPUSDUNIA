<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class EmployeeExitNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
            'type' => $this->data['type'] ?? 'employee_exit',
            'title' => $this->data['title'] ?? 'Exit Notification',
            'message' => $this->data['message'] ?? '',
            'institute_id' => $this->data['institute_id'] ?? null,
            'module' => $this->data['module'] ?? 'employee_exit',
            'timestamp' => $this->data['timestamp'] ?? Carbon::now()->toIso8601String(),
            'icon' => $this->data['icon'] ?? 'bell',
            'color' => $this->data['color'] ?? 'secondary',
            'exit_id' => $this->data['exit_id'] ?? null,
            'action_url' => $this->data['action_url'] ?? '#',
        ];
    }
}