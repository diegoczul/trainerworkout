<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use SendGrid;
use SendGrid\Mail\Mail;

class NewUserMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $password;
    public $subject;

    public function __construct($user,$password, $subject)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->password = $password;
    }

    public function handle(): void
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->user->email);
            $content = View::make('emails.' . config("app.whitelabel") . '.user.' . App::getLocale() . '.newFBUser', ['user' => $this->user, 'password' => $this->password, 'name' => $this->user->firstName])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        } catch (\Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
