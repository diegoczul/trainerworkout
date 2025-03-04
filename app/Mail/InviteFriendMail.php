<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteFriendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invite;
    public $user;
    public $name;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($subject,$invite, $user, $name)
    {
        $this->invite = $invite;
        $this->user = $user;
        $this->name = $name;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.' . config("app.whitelabel") . '.user.' . app()->getLocale() . '.inviteFriend')
            ->with([
                'invite' => $this->invite,
                'user' => $this->user,
                'name' => $this->name,
            ]);
    }
}
