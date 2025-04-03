<?php

namespace App\Mail;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use SendGrid;
use SendGrid\Mail\Mail;

class ActivationEmail extends Mailable
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
//        return $this->view('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.activateEmail')
//            ->to($this->user->email)
//            ->with(['user' => $this->user])
//            ->subject(__('messages.TrainerWorkoutEmailConfirmation'));
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject(__('messages.TrainerWorkoutEmailConfirmation'));
            $email->addTo($this->user->email);
            $content = view('emails.' . config('app.whitelabel') . '.user.' . $this->lang . '.activateEmail', ['user' => $this->user])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
