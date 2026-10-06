<?php

namespace App\Listeners;
use App\Events\StudentCreated;
use App\Models\User;
use App\Notifications\ActivityNotification;

class SendStudentCreatedNotification
{
    public function handle(StudentCreated $event)
    {
        // Get all users with 'admin' role via Spatie
        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new ActivityNotification(
                "🎓 New Student Registered: {$event->student->first_name} {$event->student->last_name}"
            ));
        }
    }
}
