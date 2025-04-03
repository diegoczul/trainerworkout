<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class TestMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $toMail;

    /**
     * Create a new message instance.
     */
    public function __construct($toMail)
    {
        $this->toMail = $toMail;
    }

    public function handle(): void
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject("Test Mail To User");
            $email->addTo($this->toMail);
            $email->addContent("text/html", "Test Mail");

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        } catch (Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
