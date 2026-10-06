<?php

namespace App\Notifications;

use App\Models\Notice;
use Illuminate\Notifications\Notification;

class NoticeCreatedNotification extends BaseNotification
{
    protected $notice;

    public function __construct(Notice $notice)
    {
        $this->notice = $notice;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'notice_created',
            'title' => 'New Notice Published',
            'message' => "New notice: {$this->notice->title}",
            'notice_id' => $this->notice->id,
            'notice_title' => $this->notice->title,
            'content' => substr($this->notice->content, 0, 100) . '...',
            'recipient_type' => $this->notice->recipient_type,
            'notice_type' => $this->notice->notice_type,
            'department_id' => $this->notice->department_id,
            'department_category_id' => $this->notice->department_category_id,
            'institute_id' => $this->notice->institute_id,
            'branch_id' => $this->notice->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('notice-board.view', $this->notice->id),
            'icon' => 'bullhorn',
            'color' => 'warning'
        ];
    }
}