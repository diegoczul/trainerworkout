<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SendGrid;
use SendGrid\Mail\Mail;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $url;

    public function __construct($email,$url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    public function build()
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject("Forgot Password");
            $email->addTo($this->email);
            $content = view('emails.default.auth.reset-password', ['url' => $this->url])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
