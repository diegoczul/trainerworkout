<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $comments;
    public $password;
    public $invite;
    public $user;
    public $fake;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($subject,$comments, $invite, $user, $fake)
    {
        $this->comments = $comments;
        $this->password = null;
        $this->invite = serialize($invite);
        $this->user = serialize($user);
        $this->fake = serialize($fake);
        $this->subject = $subject;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.' . config("app.whitelabel") . '.user.' . app()->getLocale() . '.inviteClient')
            ->with([
                'comments' => $this->comments,
                'password' => $this->password,
                'invite' => $this->invite,
                'user' => $this->user,
                'fake' => $this->fake,
            ]);
    }
}
