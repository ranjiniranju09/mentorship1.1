<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentorVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $verificationUrl;

    public function __construct($name, $verificationUrl)
    {
        $this->name = $name;
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->view('emails.mentorverificationmail')
            ->subject('Welcome to Mentorship Platform - Verify Your Registration')
            ->with([
                'name' => $this->name,
                'verification_url' => $this->verificationUrl,
            ]);
    }
}
