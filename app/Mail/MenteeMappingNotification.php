<?php

namespace App\Mail;

//use App\Models\Mapping;
use App\Mapping;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MenteeMappingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mapping;

    public function __construct(Mapping $mapping)
    {
        $this->mapping = $mapping;
    }

    public function build()
    {
        return $this->subject('You Have Been Assigned a Mentor')
                    ->view('emails.menteeMappingNotification');
    }
}
