<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class ResetPasswordMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $url;

    public function __construct($email,$url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    public function handle(): void
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject("Forgot Password");
            $email->addTo($this->email);
            $content = view('emails.default.auth.reset-password', ['url' => $this->url])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        } catch (\Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
