<?php

namespace App\Events;

use App\Models\StudentParentDetails;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentCreated
{
    use Dispatchable, SerializesModels;

    public $student;

    public function __construct(StudentParentDetails $student)
    {
        $this->student = $student;
    }
}
