<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

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
//        return $this->subject("Feedback sent $this->date")->view('ControlPanel.emails.feedback');
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject("Feedback sent $this->date");
            $email->addTo($this->user->email);
            $content = View::make('ControlPanel.emails.feedback', ['date' => $this->date, 'user' => $this->user, 'feedback' => $this->feedback])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
