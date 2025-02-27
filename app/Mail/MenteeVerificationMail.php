<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MenteeVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $verification_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $verification_url)
    {
        $this->name = $name;
        $this->verification_url = $verification_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
                    
        return $this->view('emails.menteeverificationmail')
            ->subject('Welcome to Mentorship Platform - Verify Your Registration')
            ->with([
                'name' => $this->name,
                'verification_url' => $this->verification_url,
            ]);
    }
}
