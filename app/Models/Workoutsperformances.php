<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Users;
use Workouts;

class Workoutsperformances extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [];

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'userId');
    }

    public function trainer()
    {
        return $this->hasOne(Users::class, 'id', 'forTrainer');
    }

    public function workout()
    {
        return $this->hasOne(Workouts::class, 'id', 'workoutId');
    }

    public function rating()
    {
        return $this->hasOne(Ratings::class, 'id', 'ratingId');
    }

    public function notifyTrainerPerformance()
    {
        $fromUser = $this->user;
        $toUser = $this->trainer;
        $workout = $this->workout;
        $workoutPerformance = $this;
        $rating = $this->rating;
        $ratingString = $rating ? $rating->name : '';

        $client = Clients::where('trainerId', $toUser->id)
            ->where('userId', $fromUser->id)
            ->first();

        if ($client && $client->subscribeClient == 1) {
            $to_user = $toUser->email;
            $name = !empty($fromUser->firstName) ? $fromUser->getCompleteName() : $fromUser->email;
            $subject = Lang::get('content.emailWorkoutPerformed', [
                'name' => $name,
                'workout' => $workout->name,
            ]);

            Mail::queueOn(
                App::environment(),
                'emails.' . Config::get('app.whitelabel') . '.user.' . App::getLocale() . '.workoutPerformed',
                [
                    'toUser' => serialize($toUser),
                    'fromUser' => serialize($fromUser),
                    'workout' => serialize($workout),
                    'performance' => serialize($workoutPerformance),
                    'rating' => serialize($rating),
                    'ratingString' => $ratingString,
                ],
                function ($message) use ($to_user, $fromUser, $subject) {
                    $message->to($to_user)
                        ->replyTo($fromUser->email, $fromUser->getCompleteName())
                        ->subject($subject);
                }
            );

            Event::dispatch('notifyActivity', [Auth::user(), $toUser]);
        }
    }
}
