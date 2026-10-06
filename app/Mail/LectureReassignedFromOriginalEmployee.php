<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LectureReassignedFromOriginalEmployee extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct($details, $employee)
    {
        $this->details = $details;
        $this->employee = $employee;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Lecture Has Been Reassigned')
                    ->view('emails.lecture-reassigned-from-original-employee')
                    ->with([
                        'details' => $this->details,
                        'employee' => $this->employee
                    ]);
    }
}