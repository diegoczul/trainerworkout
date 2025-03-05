<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

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
        return $this->subject($this->subject)
            ->view('emails.' . config("app.whitelabel") . '.user.' . app()->getLocale() . '.newFBUser')
            ->with(['user' => $this->user,'password' => $this->password, 'name' => $this->user->firstName]);
    }
}
