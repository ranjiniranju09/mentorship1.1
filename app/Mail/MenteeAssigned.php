<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MenteeAssigned extends Mailable
{
    use Queueable, SerializesModels;


    public $menteeName;
    /**
     * Create a new message instance.
     */
    public function __construct($menteeName)
    {
        $this->menteeName = $menteeName;
    }

    public function build()
    {
        return $this->view('emails.mentee_assigned')->subject(' Welcome to Mentorship!')->with('mentorName', $this->menteeName);

    }
}
