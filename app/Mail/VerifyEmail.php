<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerifyEmail extends Mailable
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build()
    {
        $url = route('email.verify', ['token' => $this->token]);

        return $this->subject('Verify Your Email Address')
                    ->view('emails.verify_email', ['url' => $url]);
    }
}
