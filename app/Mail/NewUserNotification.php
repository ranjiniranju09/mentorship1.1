<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $role;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $role)
    {
        $this->user = $user; // This is an object containing the user details
        $this->role = $role; // This is a string
    }


    public function build()
    {
        return $this->subject('New User Registered')
                    ->view('emails.newusernotification')
                    ->with([
                        'name' => $this->user->name, // Accessing the name property of the user object
                        'email' => $this->user->email, // Accessing the email property of the user object
                        'role' => $this->role,
                    ]);
    }


}
