<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class InviteClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $comments;
    public $password;
    public $invite;
    public $user;
    public $fake;
    public $subject;
    public $lang;

    /**
     * Create a new message instance.
     */
    public function __construct($subject,$comments, $invite, $user, $fake,$lang)
    {
        $this->comments = $comments;
        $this->password = null;
        $this->invite = serialize($invite);
        $this->user = serialize($user);
        $this->fake = serialize($fake);
        $this->subject = $subject;
        $this->lang = $lang;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        try {
//            return $this->subject($this->subject)
//                ->view('emails.' . config("app.whitelabel") . '.user.' . $this->lang . '.inviteClient')
//                ->with([
//                    'comments' => $this->comments,
//                    'password' => $this->password,
//                    'invite' => $this->invite,
//                    'user' => $this->user,
//                    'fake' => $this->fake,
//                ]);

            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo(unserialize($this->user)->email);
            $content = View::make('emails.' . config("app.whitelabel") . '.user.' . $this->lang . '.inviteClient', ['comments' => $this->comments, 'password' => $this->password, 'invite' => $this->invite, 'user' => $this->user, 'fake' => $this->fake])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        }catch (\Exception $exception){
            Log::driver('email_exceptions_log')->error($exception->getMessage());
            Log::driver('email_exceptions_log')->error($exception);
        }
    }
}
