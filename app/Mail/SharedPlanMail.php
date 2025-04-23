<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class SharedPlanEmail extends Mailable
{
    public $sharing;
    public $invite;
    public $toUser;
    public $fromUser;
    public $comments;
    public $subject;
    public $copyMe;

    public function __construct(
        $sharing,
        $invite,
        $toUser,
        $fromUser,
        $comments,
        $subject,
        $copyMe = true
    ) {
        $this->sharing = $sharing;
        $this->invite = $invite;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
        $this->comments = $comments;
        $this->subject = $subject;
        $this->copyMe = $copyMe;
    }

    public function build()
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email);

            if ($this->copyMe && $this->fromUser->email !== $this->toUser->email) {
                $email->addCc($this->fromUser->email);
            }

            $content = View::make('emails.shared_plan', [
                'sharing' => $this->sharing,
                'invite' => $this->invite,
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'comments' => $this->comments,
            ])->render();

            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);

            return $response->statusCode();
        } catch (\Exception $exception) {
            $time = now()->format('d-m-Y H:i:s');
            Log::channel('email_exceptions_log')->error("[$time] SendGrid Plan Email Error", [
                'error' => $exception->getMessage(),
                'line' => $exception->getLine()
            ]);
            return 'Error: ' . $exception->getMessage();
        }
    }
}
