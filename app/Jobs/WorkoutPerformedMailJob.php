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

class WorkoutPerformedMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subject, $toUser, $fromUser, $workout, $performance, $rating, $ratingString,$lang;

    public function __construct($subject,$toUser, $fromUser, $workout, $performance, $rating, $ratingString,$lang)
    {
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
        $this->workout = $workout;
        $this->performance = $performance;
        $this->rating = $rating;
        $this->ratingString = $ratingString;
        $this->subject = $subject;
        $this->lang = $lang;
    }

    public function handle(): void
    {
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Trainer Workout'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email, $this->toUser->name);
            $content = view('emails.' . Config::get('app.whitelabel') . '.user.' . $this->lang . '.workoutPerformed', ['toUser' => $this->toUser, 'fromUser' => $this->fromUser, 'workout' => $this->workout, 'performance' => $this->performance, 'rating' => $this->rating, 'ratingString' => $this->ratingString])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $sendgrid->send($email);
        } catch (\Exception $exception) {
            $time = now('Asia/Kolkata')->format('d-m-Y H:i:s');
            Log::driver('email_exceptions_log')->error("[$time] : Email Exception : ",['error' => $exception->getMessage(), 'line' => $exception->getLine(),]);
        }
    }
}
