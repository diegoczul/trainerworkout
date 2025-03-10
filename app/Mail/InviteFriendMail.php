<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class InviteFriendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invite;
    public $user;
    public $name;
    public $subject;
    public $lang;

    /**
     * Create a new message instance.
     */
    public function __construct($subject,$invite, $user, $name, $lang)
    {
        $this->invite = $invite;
        $this->user = $user;
        $this->name = $name;
        $this->subject = $subject;
        $this->lang = $lang;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.' . config("app.whitelabel") . '.user.' .$this->lang. '.inviteFriend')
            ->with([
                'invite' => $this->invite,
                'user' => $this->user,
                'name' => $this->name,
            ]);
    }
}
