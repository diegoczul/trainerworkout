<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

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
        return $this->view('emails.' . Config::get("app.whitelabel") . '.user.' . $this->lang . '.activateEmail')
            ->with(['user' => $this->user])
            ->subject(__('messages.TrainerWorkoutEmailConfirmation'));
    }
}
