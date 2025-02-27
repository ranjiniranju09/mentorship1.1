<?php

namespace App\Mail;

use App\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SessionCreatedMentor extends Mailable
{
    use Queueable, SerializesModels;

    public $session;

        public function __construct($session)
    {
        $this->session = (object) $session; // Ensure it's treated as an object
    }


    public function build()
    {
        return $this->subject('New Session Created')
                    ->view('emails.session_created_mentor', ['session' => $this->session]);
    }
}