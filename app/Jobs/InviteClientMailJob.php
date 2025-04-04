<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class InviteClientMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $comments;
    public $password;
    public $invite;
    public $user;
    public $fake;
    public $subject;
    public $lang;

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

    public function handle(): void
    {
        try {
            $user = unserialize($this->user);
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($user->email);
            $content = View::make('emails.' . config("app.whitelabel") . '.user.' . $this->lang . '.inviteClient', ['comments' => $this->comments, 'password' => $this->password, 'invite' => $this->invite, 'user' => $this->user, 'fake' => $this->fake])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        }catch (\Exception $exception){
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
