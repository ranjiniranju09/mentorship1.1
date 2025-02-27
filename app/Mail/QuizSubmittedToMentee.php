<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuizSubmittedToMentee extends Mailable
{
    use Queueable, SerializesModels;

    public $mentee;
    public $score;

    /**
     * Create a new message instance.
     *
     * @param $mentee
     * @param $score
     */
    public function __construct($mentee, $score)
    {
        $this->mentee = $mentee;
        $this->score = $score;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Quiz Submission Details')
                    ->view('emails.quizSubmittedToMentee')
                    ->with([
                        'mentee' => $this->mentee,
                        'score' => $this->score,
                    ]);
    }
}
