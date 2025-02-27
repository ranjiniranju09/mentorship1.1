<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAddedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $mentorName;
    public $menteeName;

    public function __construct($task, $mentorName,$menteeName)
    {
        $this->task = $task;
        $this->mentorName = $mentorName;
        $this->menteeName = $menteeName;
    }

    public function build()
    {
        return $this->view('emails.task_added')
        ->with([
            'menteeName' => $this->menteeName,
            'taskTitle' => $this->task->title,
            'taskDescription' => $this->task->description,
            'mentorName' => $this->mentorName,
        ])
        ->subject('New Task Assigned');
    }
}