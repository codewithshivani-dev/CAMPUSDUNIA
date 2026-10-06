<?php

namespace App\Notifications;

use App\Models\HolidayEvent;
use Illuminate\Notifications\Notification;

class EventCreatedNotification extends BaseNotification
{
    protected $event;
    protected $action;

    public function __construct(HolidayEvent $event, $action = 'created')
    {
        $this->event = $event;
        $this->action = $action;
    }

    public function toDatabase($notifiable)
    {
        $eventType = ucfirst($this->event->type);
        $eventScope = ucfirst($this->event->scope);
        $dateRange = date('d M Y', strtotime($this->event->start_date));
        
        if ($this->event->end_date && $this->event->end_date != $this->event->start_date) {
            $dateRange .= ' to ' . date('d M Y', strtotime($this->event->end_date));
        }

        $targetInfo = '';
        if ($this->event->scope === 'department_wise' && $this->event->department) {
            $targetInfo = ' for ' . $this->event->department->department;
            if ($this->event->departmentCategory) {
                $targetInfo .= ' (' . $this->event->departmentCategory->name . ')';
            }
        } elseif ($this->event->scope === 'overall') {
            $targetInfo = ' for all departments';
        }

        return [
            'type' => 'event_created',
            'title' => 'New ' . $eventType . ' ' . $this->action,
            'message' => $this->event->title . ' has been scheduled on ' . $dateRange . $targetInfo,
            'event_id' => $this->event->holiday_event_id,
            'event_title' => $this->event->title,
            'event_type' => $this->event->type,
            'event_scope' => $this->event->scope,
            'start_date' => $this->event->start_date,
            'end_date' => $this->event->end_date,
            'color' => $this->event->color,
            'description' => $this->event->description,
            'department_id' => $this->event->department_id,
            'department_name' => $this->event->department->department ?? null,
            'department_category_id' => $this->event->department_category_id,
            'department_category_name' => $this->event->departmentCategory->name ?? null,
            'institute_id' => $this->event->institute_id,
            'branch_id' => $this->event->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('institute-admin.google-calendar', ['date' => $this->event->start_date]),
            'icon' => $this->getIconForEventType($this->event->type),
            'color' => $this->getColorForEventType($this->event->type)
        ];
    }

    private function getIconForEventType($type)
    {
        $icons = [
            'holiday' => 'umbrella-beach',
            'event' => 'calendar-star',
            'meeting' => 'users',
            'other' => 'calendar-alt'
        ];
        return $icons[$type] ?? 'calendar-alt';
    }

    private function getColorForEventType($type)
    {
        $colors = [
            'holiday' => 'success',
            'event' => 'info',
            'meeting' => 'warning',
            'other' => 'secondary'
        ];
        return $colors[$type] ?? 'primary';
    }
}