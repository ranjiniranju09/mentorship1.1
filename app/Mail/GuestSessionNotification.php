<?php

namespace App\Mail;

use App\GuestLecture;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestSessionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $guestLecture;

    public function __construct(GuestLecture $guestLecture)
    {
        $this->guestLecture = $guestLecture;
    }

    public function build()
    {
        //return $this->subject('New Guest Session Details')
          //          ->view('emails.guestSessionNotification');

        return $this->view('emails.guestSessionNotification')
                    ->with([
                        'guestLecture' => $this->guestLecture,
                    ]);
    }
}
