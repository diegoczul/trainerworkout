<?php

namespace App\Http\Controllers\webview;

use App\Http\Controllers\Controller;
use App\Http\Controllers\web\SystemController;
use App\Http\Libraries\Helper;
use App\Models\BodyGroups;
use App\Models\Equipments;
use App\Models\ExercisesTypes;
use App\Models\Tags;
use App\Models\Users;
use App\Models\Workouts;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class WebviewController extends Controller
{
    // Create Workout Screen
    public function createTrainerWorkout($user)
    {
        $userId = Helper::decodeUserSlug($user);
        if(Users::find($userId)){
            Auth::loginUsingId($userId);
        }else{
            abort(403);
        }

        $permissions = Helper::checkPremissions($userId,null);
        $tags = Tags::where("userId",$userId)->get();

        if(Session::has("workoutIdInProgress") && !empty(Session::has("workoutIdInProgress"))){
            $workout = Workouts::find(Session::get("workoutIdInProgress"));
        }else{
            // Workout
            $workout = new Workouts();
            $workout->name = "";
            $workout->sale = 0;
            $workout->availability = "private";
            $workout->shares = 0;
            $workout->views = 0;
            $workout->timesPerformed = 0;
            $workout->userId = $userId;
            $workout->authorId = $userId;
            $workout->status = "Draft";
            $workout->version = Config::get("constants.version");
            $workout->save();
            $workout->master = $workout->id;
            $workout->save();
            Session::put("workoutIdInProgress",$workout->id);
            Session::save();
        }

        return view("webview.create-workout")
            ->with("workout",$workout)
            ->with("permissions",$permissions)
            ->with("tags",$tags)
            ->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
            ->with("bodygroupslist",BodyGroups::select(["id","name"])->where("main",1)->orderBy("name")->pluck("name","id"))
            ->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
            ->with("equipmentsList",Equipments::select("id","name")->orderBy("name")->pluck("name","id"))
            ->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
            ->with("total",Workouts::where("userId","=",$userId)->count());
    }

    public function workoutCreatedSuccessfully()
    {
        return"<h1>Workout Created Successfully</h1>";
    }

    public function failedToCreateWorkout()
    {
        return"<h1>Failed to Create Workout</h1>";
    }

    public function failedToUpdateWorkout()
    {
        return"<h1>You do not have permission to edit this workout</h1>";
    }

    public function editTrainerWorkout($user_id,$workout_id)
    {
        $userId = Helper::decodeUserSlug($user_id);
        if(Users::find($userId)){
            Auth::loginUsingId($userId);
        }else{
            abort(403);
        }
        $userId = Auth::user()->id;
        $workout = Workouts::find($workout_id);
        if($workout->canThisWorkoutBeShared(Auth::user())){
            $tags = array();

            if($workout){
                if(date($workout->created_at) <= date('2016-09-04') and $workout->status == "Released"){
                    $controller = new SystemController();
                    $controller->migrateWorkouts($workout->id);
                } else if($workout->exerciseGroupRest == "" or $workout->exerciseGroupsRest == "[]"){
                    $controller = new SystemController();
                    $controller->migrateWorkouts($workout->id);
                }
            } else {
                return redirect()->route('webview.edit-trainer-workout-unauthorised');
            }


            $tags = Tags::where("userId",Auth::user()->id)->get();

            Event::dispatch('editAWorkout', array(Auth::user(),$workout->name));

            return view("webview.edit-workout")
                ->with("workout",$workout)
                ->with("tags",$tags)
                ->with("bodygroups",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->get())
                ->with("bodygroupslist",BodyGroups::select("id","name")->where("main",1)->orderBy("name")->pluck("name","id"))
                ->with("equipmentsList",Equipments::select("id","name")->pluck("name","id"))
                ->with("equipments",Equipments::select("id","name")->orderBy("name")->get())
                ->with("exercisesTypes",ExercisesTypes::select("id","name")->orderBy("name")->get())
                ->with("total",Workouts::where("userId","=",$userId)->count());
        }else{
            return redirect()->route('webview.edit-trainer-workout-unauthorised');
        }
    }
}
