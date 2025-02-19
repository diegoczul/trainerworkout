<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;


    public $date, $user, $feedback;

    public function __construct($date, $user, $feedback)
    {
        $this->date = $date;
        $this->user = $user;
        $this->feedback = $feedback;
    }

    public function build()
    {
        return $this->subject("Feedback sent $this->date")->view('ControlPanel.emails.feedback');
    }
}
