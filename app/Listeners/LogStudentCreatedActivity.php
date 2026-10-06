<?php

namespace App\Listeners;

use App\Events\StudentCreated;

class LogStudentCreatedActivity
{
    public function handle(StudentCreated $event)
    {
        activity()
            ->causedBy(auth()->user())    // who created the student
            ->performedOn($event->student) // the student model
            ->withProperties(['email' => $event->student->email])
            ->log("🎓 Student {$event->student->first_name} {$event->student->last_name} was registered");
    }
}
