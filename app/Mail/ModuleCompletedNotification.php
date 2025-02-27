<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModuleCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mentor;
    public $mentee;
    public $module;

    public function __construct($mentor, $mentee, $module)
    {
        $this->mentor = $mentor;
        $this->mentee = $mentee;
        $this->module = $module;
    }

    public function build()
    {
        return $this->view('emails.moduleCompletedNotification')
        ->subject('Module Completed Notification');
    }
}
