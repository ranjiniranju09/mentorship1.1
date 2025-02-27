<?php

namespace App\Mail;

use App\Mapping;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MentorMappingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mapping;

    public function __construct(Mapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function build()
    {
        return $this->subject('New Mentee Assigned')
                    ->view('emails.mentorMappingNotification');
    }
}
