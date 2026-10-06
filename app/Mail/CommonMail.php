<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommonMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $viewName;
    public array $data;

    /**
     * @param string $viewName  Blade template name
     * @param array  $data      Data for blade
     * @param string $subject   Email subject
     */
    public function __construct(
        string $viewName,
        array $data,
        string $subject
    ) {
        $this->viewName = $viewName;
        $this->data = $data;
        $this->subject($subject);
    }

    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->view($this->viewName)
            ->with($this->data);
    }
}
