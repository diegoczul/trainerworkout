<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

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
//        return $this->subject($this->subject)
//            ->view('emails.' . config("app.whitelabel") . '.user.' .$this->lang. '.inviteFriend')
//            ->with([
//                'invite' => $this->invite,
//                'user' => $this->user,
//                'name' => $this->name,
//            ]);
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->user->email);
            $content = View::make('emails.' . config("app.whitelabel") . '.user.' . $this->lang . '.inviteFriend', ['invite' => $this->invite, 'user' => $this->user, 'name' => $this->name])->render();
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
