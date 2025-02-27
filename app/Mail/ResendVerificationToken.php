<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResendVerificationToken extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $verification_url;
    public $role;

    /**
     * Create a new message instance.
     *
     * @param string $name
     * @param string $verification_url
     * @param string $role
     */
    public function __construct($name, $verification_url, $role)
    {
        $this->name = $name;
        $this->verification_url = $verification_url;
        $this->role = $role;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.resend_verification')
                    ->subject('Verify Your Email Address')
                    ->with([
                        'name' => $this->name,
                        'verification_url' => $this->verification_url,
                        'role' => $this->role,
                    ]);
    }
}
