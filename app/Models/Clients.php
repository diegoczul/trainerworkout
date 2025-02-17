<?php

namespace App\Models;

use App\Http\Libraries\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use SoftDeletes;

    protected $fillable = [];

    public function user()
    {
        return $this->hasOne(Users::class, 'id', 'userId');
    }

    public function trainer()
    {
        return $this->hasOne(Users::class, 'id', 'trainerId');
    }

    public static function checkIfTrainerHasClient($trainerId, $userId)
    {
        return self::where("trainerId", $trainerId)->where("userId", $userId)->exists();
    }

    public static function checkIfTraineeHasTrainer($trainee, $trainer)
    {
        return self::where("trainerId", $trainer)->where("userId", $trainee)->exists();
    }

    public function numberOfWorkoutsSharedFromTrainerToClient($trainerId)
    {
        return Workouts::where("userId", $this->userId)->where("authorId", $trainerId)->count();
    }

    public function lastWorkoutPerformedFromTrainer($trainerId)
    {
        $workoutPerformance = Workoutsperformances::where("userId", $this->userId)
            ->where("forTrainer", $trainerId)
            ->orderBy("created_at", "Desc")
            ->first();

        if ($workoutPerformance) {
            $now = time();
            $your_date = strtotime($workoutPerformance->created_at);
            $datediff = $now - $your_date;
            return floor($datediff / (60 * 60 * 24));
        } else {
            return -1;
        }
    }

    public function latestWorkoutSharedName($trainerId)
    {
        $workout = Workouts::where("userId", $this->userId)->where("authorId", $trainerId)->orderBy("created_at", "DESC")->first();
        return $workout ? $workout->name : trans('messages.NoWorkoutShared');
    }

    public static function returnAllTrainersOfClient($clientId)
    {
        return self::select("trainerId")->with("trainer")->where("userId", $clientId)->distinct()->get();
    }

    public static function trainerAddWorkoutToClient($trainerId, $userId, $workoutId)
    {
        $workout = Workouts::find($workoutId);
        if ($workout) {
            $workoutNew = $workout->replicate();
            $workoutNew->userId = $userId;
            $workoutNew->authorId = $trainerId;
            $workoutNew->availability = 'private';
            $workoutNew->parentWorkout = $workout->id;
            $workoutNew->save();

            $workout->increment('shares');

            $workoutExercises = WorkoutsExercises::where("workoutId", $workout->id)->get();
            foreach ($workoutExercises as $workoutExercise) {
                $workoutExerciseNew = $workoutExercise->replicate();
                $workoutExerciseNew->workoutId = $workoutNew->id;
                $workoutExerciseNew->save();

                $templateSets = TemplateSets::where("workoutsExercisesId", $workoutExercise->id)->get();
                foreach ($templateSets as $templateSet) {
                    $templateSetNew = $templateSet->replicate();
                    $templateSetNew->workoutId = $workoutNew->id;
                    $templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
                    $templateSetNew->save();
                }
            }

            $workoutNew->createSets();
        }
    }

    public function subscribeToClient($trainerId, $subscribe = true)
    {
        $update = UserUpdates::where("trainerId", $trainerId)
            ->where("auxId", $this->id)
            ->where("type", "client")
            ->first();

        if (!$update) {
            $update = new UserUpdates;
            $update->trainerId = $trainerId;
            $update->userId = $this->userId;
            $update->auxId = $this->id;
            $update->parentAuxId = $this->master;
            $update->type = "client";
        }

        $update->subscribe = (bool) $subscribe;
        $update->save();
    }

    public function link()
    {
        return "/Client/{$this->user->id}/" . Helper::formatURLString($this->user->getCompleteName());
    }
}
