<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentorAssigned extends Mailable
{
    use Queueable, SerializesModels;


    public $mentorName;
    /**
     * Create a new message instance.
     */
    public function __construct($mentorName)
    {
        $this->mentorName = $mentorName;
    }

    
    public function build()
    {
        return $this->view('emails.mentor_assigned')->subject(' Welcome to Mentorship!')->with('mentorName', $this->mentorName);

    }
}
