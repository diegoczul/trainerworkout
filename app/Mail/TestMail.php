<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $toMail;
    /**
     * Create a new message instance.
     */
    public function __construct($toMail)
    {
        $this->toMail = $toMail;
    }

   public function build()
   {
       try {
           $email = new Mail();
           $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
           $email->setSubject("Test Mail To User");
           $email->addTo($this->toMail);
           $email->addContent("text/html", "Test Mail");

           $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
           $response = $sendgrid->send($email);
           return $response->statusCode();
       } catch (\Exception $exception) {
           $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
           Log::error("[$time] SendGrid Email Error: " . $exception->getMessage());
           return 'Error: ' . $exception->getMessage();
       }
   }
}
