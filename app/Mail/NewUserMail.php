<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class NewUserMail extends Mailable
{
    public $user;
    public $password;
    public $subject;

    public function __construct($user,$password, $subject)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->password = $password;
    }

    public function build()
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->user->email);
            $content = View::make('emails.' . config("app.whitelabel") . '.user.' . App::getLocale() . '.newFBUser', ['user' => $this->user, 'password' => $this->password, 'name' => $this->user->firstName])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (\Exception $exception) {
            Log::error("SendGrid Email Error: " . $exception->getMessage());
            return 'Error: ' . $exception->getMessage();
        }

//        return $this->subject($this->subject)
//            ->view('emails.' . config("app.whitelabel") . '.user.' . App::getLocale() . '.newFBUser')
//            ->with(['user' => $this->user,'password' => $this->password, 'name' => $this->user->firstName]);
    }
}
