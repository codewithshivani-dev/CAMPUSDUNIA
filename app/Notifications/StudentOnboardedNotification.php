<?php
// app/Notifications/StudentOnboardedNotification.php

namespace App\Notifications;

use App\Models\Student;
use Illuminate\Notifications\Notification;

class StudentOnboardedNotification extends BaseNotification
{
    protected $student;
    protected $action;

    public function __construct($student, $action = 'added')
    {
        $this->student = $student;
        $this->action = $action;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'student_onboarding',
            'title' => 'New Student Onboarded',
            'message' => "New student {$this->student->name} (Roll No: {$this->student->roll_number}) has been {$this->action}",
            'student_id' => $this->student->student_id,
            'student_name' => $this->student->name,
            'department_id' => $this->student->department_id ?? null,
            'department_category_id' => $this->student->department_category_id ?? null,
            'course' => $this->student->course ?? null,
            'institute_id' => $this->student->institute_id,
            'branch_id' => $this->student->branch_id,
            'time' => now()->toDateTimeString(),
            'action_url' => route('students.show', $this->student->student_id),
            'icon' => 'graduation-cap',
            'color' => 'blue'
        ];
    }
}