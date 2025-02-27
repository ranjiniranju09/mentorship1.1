<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuizSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $mentee;
    public $mentor;
    public $quizResult; // Corrected

    public function __construct($mentee, $mentor, $quizResult) // Corrected
    {
        $this->mentee = $mentee;
        $this->mentor = $mentor;
        $this->quizResult = $quizResult; 
    }

    public function build()
{
    return $this->subject('Quiz Submitted Notification')
                ->view('emails.quizSubmittedNotification')
                ->with([
                    'mentor' => $this->mentor,
                    'mentee' => $this->mentee,
                    'quizResult' => $this->quizResult, // Pass the correct quiz result object
                ]);
}
}
