<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

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
        return $this->subject($this->subject)
            ->view('emails.' . Config::get('app.whitelabel') . '.user.' . $this->lang . '.workoutPerformed')
            ->with([
                'toUser' => $this->toUser,
                'fromUser' => $this->fromUser,
                'workout' => $this->workout,
                'performance' => $this->performance,
                'rating' => $this->rating,
                'ratingString' => $this->ratingString,
            ]);
    }
}