<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use SendGrid;
use SendGrid\Mail\Mail;

class WorkoutPerformedMail extends Mailable
{
    use Queueable, SerializesModels;

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

    public function build()
    {
//        return $this->subject($this->subject)
//            ->view('emails.' . Config::get('app.whitelabel') . '.user.' . $this->lang . '.workoutPerformed')
//            ->with([
//                'toUser' => $this->toUser,
//                'fromUser' => $this->fromUser,
//                'workout' => $this->workout,
//                'performance' => $this->performance,
//                'rating' => $this->rating,
//                'ratingString' => $this->ratingString,
//            ]);
        try {
            $email = new Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Trainer Workout'));
            $email->setSubject($this->subject);
            $email->addTo($this->toUser->email, $this->toUser->name);
            $content = view('emails.' . Config::get('app.whitelabel') . '.user.' . $this->lang . '.workoutPerformed', ['toUser' => $this->toUser, 'fromUser' => $this->fromUser, 'workout' => $this->workout, 'performance' => $this->performance, 'rating' => $this->rating, 'ratingString' => $this->ratingString])->render();
            $email->addContent("text/html", $content);

            $sendgrid = new SendGrid(env('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}