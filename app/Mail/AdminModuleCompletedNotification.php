<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminModuleCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mentor;
    public $mentee;
    public $module;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mentor, $mentee, $module)
    {
        $this->mentor = $mentor;
        $this->mentee = $mentee;
        $this->module = $module;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Module Completed Notification')
                    ->view('emails.adminmodulecompletednotification')
                    ->with([
                        'mentor' => $this->mentor,
                        'mentee' => $this->mentee,
                        'module' => $this->module,
                    ]);
    }
}
