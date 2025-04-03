<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class NewEmailConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lang;

    public function __construct($user,$lang)
    {
        $this->user = $user;
        $this->lang = $lang;
    }

    public function build()
    {
//        return $this->view('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.newEmailConfirmation')
//            ->to($this->user->new_email)
//            ->with(['user' => $this->user])
//            ->subject(__('messages.TrainerWorkoutEmailConfirmation'));
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject(__('messages.TrainerWorkoutEmailConfirmation'));
            $email->addTo($this->user->new_email);
            $content = View::make('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.newEmailConfirmation', ['user' => $this->user])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);

            return $response->statusCode();
        } catch (\Exception $exception) {
            Log::error("SendGrid Email Error: " . $exception->getMessage());
            return 'Error: ' . $exception->getMessage();
        }
    }
}
