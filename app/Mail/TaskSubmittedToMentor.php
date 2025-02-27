<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskSubmittedToMentor extends Mailable
{
    use Queueable, SerializesModels;

    public $mentee;
    public $taskResponse;
    public $submittedFileUrl;

    /**
     * Create a new message instance.
     *
     * @param $mentee
     * @param $taskResponse
     * @param null $submittedFileUrl
     */
    public function __construct($mentee, $taskResponse, $submittedFileUrl = null)
    {
        $this->mentee = $mentee;
        $this->taskResponse = $taskResponse;
        $this->submittedFileUrl = $submittedFileUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Task Submitted by Mentee')
                    ->view('emails.task_submitted_mentor')
                    ->with([
                        'mentee' => $this->mentee,
                        'taskResponse' => $this->taskResponse,
                        'submittedFileUrl' => $this->submittedFileUrl,
                    ]);
    }
}
