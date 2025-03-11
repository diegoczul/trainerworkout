<?php

namespace App\Models;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Libraries\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use LynX39\LaraPdfMerger\PdfManage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class Workouts extends Model
{
    use SoftDeletes;

    protected $fillable = [];
    protected $dates = ['deleted_at'];

    public static $rules = [
        "price" => "numeric",
    ];

    public function delete()
    {
        TemplateSets::where("workoutId", $this->id)->delete();
        WorkoutsExercises::where("workoutId", $this->id)->delete();
        WorkoutsGroups::where("workoutId", $this->id)->delete();
        Sets::where("workoutId", $this->id)->delete();

        return parent::delete();
    }

    public function restore()
    {
        TemplateSets::where("workoutId", $this->id)->restore();
        WorkoutsExercises::where("workoutId", $this->id)->restore();
        WorkoutsGroups::where("workoutId", $this->id)->restore();
        Sets::where("workoutId", $this->id)->restore();

        return parent::restore();
    }

    public function forceDelete()
    {
        TemplateSets::where("workoutId", $this->id)->forceDelete();
        WorkoutsExercises::where("workoutId", $this->id)->forceDelete();
        WorkoutsGroups::where("workoutId", $this->id)->forceDelete();
        Sets::where("workoutId", $this->id)->forceDelete();

        return parent::forceDelete();
    }

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function unArchive()
    {
        $this->archived_at = null;
        $this->save();
    }

    public function subcribeToWorkout($trainerId, $subscribe = true)
    {
        $update = UserUpdates::where("trainerId", $trainerId)
            ->where("auxId", $this->id)
            ->where("type", "workout")
            ->first();

        if (!$update) {
            $update = new UserUpdates;
            $update->trainerId = $trainerId;
            $update->userId = $this->userId;
            $update->auxId = $this->id;
            $update->parentAuxId = $this->master;
            $update->type = "workout";
        }

        $update->subscribe = $subscribe === "true" ? 1 : 0;
        $update->save();
    }

    public function deleteWorkoutContents()
    {
        TemplateSets::where("workoutId", $this->id)->delete();
        WorkoutsExercises::where("workoutId", $this->id)->delete();
        WorkoutsGroups::where("workoutId", $this->id)->delete();
        Sets::where("workoutId", $this->id)->delete();
    }

    public function restoreWorkout()
    {
        TemplateSets::where("workoutId", $this->id)->restore();
        WorkoutsExercises::where("workoutId", $this->id)->restore();
        WorkoutsGroups::where("workoutId", $this->id)->restore();
        Sets::where("workoutId", $this->id)->restore();
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query
//                ->select(
//                "workouts.*",
//                DB::raw("MATCH(workouts.name) AGAINST(? IN BOOLEAN MODE) AS scoreName"),
//                DB::raw("MATCH(workouts.name, workouts.description) AGAINST(? IN BOOLEAN MODE) AS scoreNameDescription"),
//                DB::raw("MATCH(workouts.description) AGAINST(? IN BOOLEAN MODE) AS scoreDescription"),
//                DB::raw("MATCH(workouts.tags) AGAINST(? IN BOOLEAN MODE) AS scoreTags"),
//                DB::raw("CHAR_LENGTH(workouts.name) AS multiplier")
//            )
                ->where(function ($query) use ($search) {
                    $query->orWhereRaw("MATCH(workouts.name) AGAINST(? IN BOOLEAN MODE) > 0", [$search . "*"])
                        ->orWhereRaw("MATCH(workouts.name, workouts.description) AGAINST(? IN BOOLEAN MODE) > 0", [$search . "*"])
                        ->orWhereRaw("MATCH(workouts.description) AGAINST(? IN BOOLEAN MODE) > 0", [$search . "*"])
                        ->orWhereRaw("MATCH(workouts.tags) AGAINST(? IN BOOLEAN MODE) > 0", [$search . "*"]);
                })
//                ->orderBy("scoreName", "DESC")
//                ->orderBy("scoreTags", "DESC")
//                ->orderBy("scoreNameDescription", "DESC")
//                ->orderBy("scoreDescription", "DESC")
//                ->orderBy("multiplier", "ASC")
                ->orderBy("shares", "DESC")
                ->orderBy("views", "DESC");
        }

        return $query;
    }

    public function duplicateTemplateFrom($workoutFrom)
    {
        $objects = WorkoutsGroups::where("workoutId", $workoutFrom->id)->get();

        foreach ($objects as $obj) {
            $newObj = $obj->replicate();
            $newObj->workoutId = $this->id;
            $newObj->save();

            $objects2 = WorkoutsExercises::where("workoutId", $workoutFrom->id)
                ->where("groupId", $obj->id)
                ->get();

            foreach ($objects2 as $obj2) {
                $newObj2 = $obj2->replicate();
                $newObj2->workoutId = $this->id;
                $newObj2->groupId = $newObj->id;
                $newObj2->save();

                $objects3 = TemplateSets::where("workoutId", $workoutFrom->id)
                    ->where("workoutsExercisesId", $obj2->id)
                    ->get();

                foreach ($objects3 as $obj3) {
                    $newObj3 = $obj3->replicate();
                    $newObj3->workoutId = $this->id;
                    $newObj3->workoutsExercisesId = $newObj2->id;
                    $newObj3->save();
                }
            }
        }
    }

    public function isOwner()
    {
        return $this->userId == Auth::id();
    }

    public function isAuthor()
    {
        return $this->authorId == Auth::id();
    }

    public function resetWorkout()
    {
        $this->shares = 0;
        $this->views = 0;
        $this->timesPerformed = 0;
        $this->timesPerWeek = 0;
        $this->averageCompleted = 0;
        $this->lastRevized = null;
        $this->timesPerformedRevized = 0;
        $this->save();
    }

    public static function validate($data)
    {
        return Validator::make($data, static::$rules);
    }

    public function templateSets()
    {
        return $this->hasMany(TemplateSets::class, "workoutId", "id");
    }

    public function workoutsExercises()
    {
        return $this->hasMany(WorkoutsExercises::class, "workoutId", "id");
    }

    public function workoutsGroups()
    {
        return $this->hasMany(WorkoutsGroups::class, "workoutId", "id");
    }

    public function sets()
    {
        return $this->hasMany(Sets::class, "workoutId", "id");
    }

    public function author()
    {
        return $this->hasOne(Users::class, "id", "authorId");
    }

    public function user()
    {
        return $this->hasOne(Users::class, "id", "userId");
    }

    public function trainer()
    {
        return $this->hasOne(Users::class, "id", "trainerMonitoringId");
    }

    public function getExercises()
    {
        return WorkoutsExercises::with(["exercises" => function ($query) {
                $query->select("id", "bodygroupId", "userId", "name", "description", "image as image_url", "image2 as image2_url", "thumb as thumb_url", "thumb2 as thumb2_url", "video", "youtube", "type", "equipment", "deleted_at", "created_at", "updated_at", "authorId", "bodyGroupSec", "views", "used", "nameEngine", "equipmentRequired", "exercisesTypesId", "secondsPerRep");
            }])
            ->where("workoutId", $this->id)
            ->orderBy("order");
    }

    public function getGroups()
    {
        return WorkoutsGroups::where("workoutId", $this->id)->orderBy("groupNumber");
    }

    public function getSets($workoutsExercisesId)
    {
        return Sets::with("workoutsExercises")
            ->where("workoutId", $this->id)
            ->where("workoutsExercisesId", $workoutsExercisesId)
            ->orderBy("number", "ASC")
            ->orderBy("id", "ASC")
            ->get();
    }

    public function getTemplateSets($workoutsExercisesId)
    {
        return TemplateSets::where("workoutId", $this->id)
            ->where("workoutsExercisesId", $workoutsExercisesId)
            ->orderBy("id", "ASC")
            ->get();
    }

    public function getExercisesImagesWidget()
    {
        $images = array_fill(0, 5, "");
        $exercises = $this->getExercises()->get();
        $index = 0;

        foreach ($exercises as $exercise) {
            $images[$index] = asset($exercise->exercises->image);
            $index++;
            if ($index > 5) {
                break;
            }
        }

        return $images;
    }

    public function createSets()
    {
        $workoutExercises = WorkoutsExercises::where("workoutId", $this->id)
            ->orderBy("id", "ASC")
            ->get();

        foreach ($workoutExercises as $workoutExercise) {
            $this->createNewSetsExercise($workoutExercise->id);
        }
    }

    public function scopeForSale($query)
    {
        return $query->where("sale", 1);
    }

    public function scopeReleased($query)
    {
        return $query->where("status", "Released");
    }

    public function scopeForSaleFree($query)
    {
        return $query->where("sale", 1)->where("price", 0);
    }


    public function scopeforSalePaid($query)
    {
        return $query->where("sale", 1)->where("price", ">", "0");
    }

    public function createNewSetsExercise($workoutsExercise)
    {
        $templateSets = TemplateSets::where("workoutId", $this->id)
            ->where("workoutsExercisesId", $workoutsExercise)
            ->orderBy("created_at", "Desc")
            ->orderBy("number", "Asc")
            ->get();

        $numberOfTemplateSets = count($templateSets);

        $sets = Sets::where("workoutId", $this->id)
            ->where("workoutsExercisesId", $workoutsExercise)
            ->orderBy("created_at", "Desc")
            ->orderBy("number", "Asc")
            ->get();

        Sets::where("workoutId", $this->id)
            ->where("workoutsExercisesId", $workoutsExercise)
            ->update(array("last" => 0));

        $index = 0;

        foreach ($templateSets as $templateSet) {
            $set = new Sets();
            $set->exerciseId = $templateSet->exerciseId;
            $set->number = count($sets) + $templateSet->number;
            $set->reps = $templateSet->reps;
            $set->metric = $templateSet->metric;

            if ($index < count($sets)) {
                $set->weight = $sets[$index]->weight;
                $set->weightKG = $sets[$index]->weightKG;
            } else {
                $set->weight = $templateSet->weight;
                $set->weightKG = $templateSet->weightKG;
            }

            $set->rest = $templateSet->rest;
            $set->tempo = $templateSet->tempo;
            $set->units = $templateSet->units;
            $set->type = $templateSet->type;
            $set->distance = $templateSet->distance;
            $set->speed = $templateSet->speed;
            $set->bpm = $templateSet->bpm;
            $set->time = $templateSet->time;
            $set->notes = $templateSet->notes;
            $set->workoutId = $templateSet->workoutId;
            $set->workoutsExercisesId = $templateSet->workoutsExercisesId;
            $set->completed = 0;
            $set->last = 0;

            if (count($templateSets) == $index + 1) {
                $set->last = 1;
            }

            $set->save();
            $index++;
        }
    }

    public function getURL()
    {
        if ($this->userId == Auth::user()->id || $this->authorId == Auth::user()->id) {
            return Lang::get("routes.Workout/") . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
                (($this->author) ? Helper::formatURLString($this->author->firstName . $this->author->lastName) : "");
        } elseif ($this->master != "") {
            $workoutMaster = Workouts::find($this->master);
            if ($workoutMaster && ($workoutMaster->userId == Auth::user()->id || $workoutMaster->authorId == Auth::user()->id)) {
                return Lang::get("routes.Workout/") . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
                    (($this->author) ? Helper::formatURLString($this->author->firstName . $this->author->lastName) : "");
            }
        }
        return Lang::get("routes.Workout/") . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
            (($this->author) ? Helper::formatURLString($this->author->firstName . $this->author->lastName) : "");
    }

    public function getEditURL()
    {
        if ($this->userId == Auth::user()->id || $this->authorId == Auth::user()->id) {
            return Lang::get("routes./editWorkout/") . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
                (($this->author) ? Helper::formatURLString($this->author->firstName . $this->author->lastName) : "");
        }
        return "#";
    }

    public function getURLImage()
    {
        return "/WorkoutInternal/" . $this->id . "/" . App::getLocale() . "/" .
            Helper::formatURLString($this->name) . "/" .
            Helper::formatURLString($this->author->firstName . $this->author->lastName);
    }

    public function getURLPrint()
    {
        return "/Workout/PrintWorkoutInternal/" . $this->id . "/" . App::getLocale();
    }

    public function getPDF()
    {
        $data["workout"] = $this;
        $data["user"] = Auth::user();
        $data["groups"] = $this->getGroups()->get();
        $data["exercises"] = $this->getExercises()->get();

        $this->incrementViews();
        $pdf = PDF::loadfile(URL::to("Workout/PrintWorkoutInternal/" . $this->id));
        $pdf->setOptions(array(
            "orientation" => "landscape",
        ));

        if (trim($this->name) != "") {
            $name = Config::get("constants.filePrefix") . Helper::formatURLString($this->name);
        } else {
            $name = Uuid::uuid4()->toString();
        }

        $name_temp = storage_path() . "/temp/" . $name . "_grid.pdf";

        if (File::exists($name_temp)) {
            File::delete($name_temp);
        }

        $pdf->save($name_temp);

        Event::dispatch('pdfWorkout', array(Auth::user(), $this->name));

        return $name_temp;
    }

    public function getImagePDF()
    {
        $workout = Workouts::find($this->id);

        $user = Users::find($workout->userId);
        $tags = $workout->tags;
        $tagsArray = explode(",",$tags);
        $tags = Tags::whereIn("name",$tagsArray)->where("userId",$workout->userId)->get();
        $tagsClient = Tags::where("type","user")->where("userId",$workout->userId)->get();
        $tagsTags = Tags::where("type","tag")->where("userId",$workout->userId)->get();
        if($user->lang != "") {
            App::setLocale($user->lang);
        } else {
            App::setLocale('en');
        }
        if($workout){
            $workout->incrementViews();
            $html = view("workoutImage")
                    ->with("workout",$workout)
                    ->with("user",$user)
                    ->with("tags",$tags)
                    ->with("tagsTags",$tagsTags)
                    ->with("tagsClient",$tagsClient)
                    ->with("groups",$workout->getGroups()->get())
                    ->with("exercises",$workout->getExercises()->get());
        } else {
            $html = "";
        }

        $pdf = PDF::loadHtml($html);

        if (trim($this->name) != "") {
            $name = Config::get("constants.filePrefix") . Helper::formatURLString($this->name);
        } else {
            $name = Uuid::uuid4()->toString();
        }
        $name_temp = storage_path() . "/temp/" . $name . ".pdf";

        if (File::exists($name_temp)) {
            File::delete($name_temp);
        }

        $pdf->save($name_temp);

        return $name_temp;
    }

    public function getPrintPDF()
    {
        $data["workout"] = $this;
        $data["user"] = Auth::user();
        $data["groups"] = $this->getGroups()->get();
        $data["exercises"] = $this->getExercises()->get();
        $pdf = PDF::loadView('workoutPrint',$data);
        $pdf->setOptions(array(
            "orientation" => "landscape",
        ));

        if (trim($this->name) != "") {
            $name = Config::get("constants.filePrefix") . Helper::formatURLString($this->name);
        } else {
            $name = Uuid::uuid4()->toString();
        }

        $name_temp = storage_path() . "/temp/" . $name . "_grid.pdf";
        if (File::exists($name_temp)) {
            File::delete($name_temp);
        }

        $pdf->save($name_temp);


        $merger = (new PdfManage())->init();
        $merger->addPDF($name_temp);
        $merger->addPDF(public_path(Config::get("constants.gridPDF")));
        $merger->merge('L', ['file' => $name_temp]);

        return $name_temp;
    }

    public function getImageScreenshot()
    {
        $data["workout"] = $this;
        $data["user"] = Auth::user();
        $data["groups"] = $this->getGroups()->get();
        $data["exercises"] = $this->getExercises()->get();

        $image = Image::make(URL::to($this->getURLImage()));


        if (trim($this->name) != "") {
            $name = Config::get("constants.filePrefix") . Helper::formatURLString($this->name);
        } else {
            $name = Uuid::uuid4()->toString();
        }

        $name_temp = storage_path() . "/temp/" . $name . ".jpg";

        if (File::exists($name_temp)) {
            File::delete($name_temp);
        }

        $image->save($name_temp);

        return $name_temp;
    }

    public function getURLPDF()
    {
        if ($this->userId == Auth::user()->id || $this->authorId == Auth::user()->id) {
            return "WorkoutPDF/" . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
                Helper::formatURLString($this->author->firstName . $this->author->lastName);
        }

        return "Workout/Preview/" . $this->id . "/" . Helper::formatURLString($this->name) . "/" .
            Helper::formatURLString($this->firstName . $this->lastName);
    }

    public function lastPerformed()
    {
        $lastDatePerformed = Sets::select("updated_at")
            ->where("workoutId", $this->id)
            ->where("completed", 1)
            ->orderBy("updated_at", "Desc")
            ->first();

        return $lastDatePerformed ? $lastDatePerformed->updated_at : date("Y-m-d h:i:s");
    }

    public function getStartedDate()
    {
        $date = WorkoutLog::where("workoutId", $this->id)
            ->where("userId", $this->userId)
            ->min("datePerformed");

        return $date ? $date : $this->created_at;
    }

    public function getCountPerformed($userId)
    {
        $count = WorkoutsPerformances::where("workoutId", $this->id)
            ->where("userId", $userId)
            ->count();

        return $count ? $count : 0;
    }

    public function getAveragePerWeek()
    {
        $dates = [];
        $dateRecords = WorkoutLog::where("workoutId", $this->id)
            ->where("userId", $this->userId)
            ->get();

        foreach ($dateRecords as $dat) {
            $da = date_create($dat->datePerformed);
            $formattedDate = date_format($da, 'Y-m-d');

            if (array_key_exists($formattedDate, $dates)) {
                $dates[$formattedDate] += 1;
            } else {
                $dates[$formattedDate] = 1;
            }
        }

        return count($dates) > 0 ? array_sum($dates) / count($dates) : 0;
    }

    public function getAverageCompleted()
    {
        $totalSets = Sets::where("workoutId", $this->id)->count();
        $completedSets = Sets::where("workoutId", $this->id)->where("completed", 1)->count();

        return $totalSets > 0 ? number_format($completedSets / $totalSets * 100, 0) : 0;
    }

    public function incrementViews()
    {
        $this->views = $this->views + 1;
        $this->save();
    }

    public function incrementShares()
    {
        $this->shares = $this->shares + 1;
        $this->save();
    }

    public function canThisWorkoutBeShared($userId)
    {
        return ($userId->id == $this->authorId || $this->lock == 0 || $this->id == 4652);
    }

    public function markAsCompleted()
    {
        $this->averageCompleted = $this->getAverageCompleted();

        if (Sets::where("workoutId", $this->id)
                ->whereBetween("created_at", [date("Y-m-d") . " 00:00:00", date("Y-m-d") . " 23:59:59"])
                ->where("completed", 1)
                ->count() <= 1
        ) {
            $this->setTimesPerWeek();
            $this->timesPerformed += 1;
            $this->timesPerformedRevized += 1;
            $this->averageCompleted = $this->getAverageCompleted();
            $this->save();

            Feeds::insertDynamicFeed(
                "WorkoutCompleted",
                Auth::user()->id,
                Auth::user()->id,
                ["firstName" => Auth::user()->firstName, "lastName" => Auth::user()->lastName, "workout" => $this->name],
                "workoutPerformed",
                $this->getURL(),
                "workout"
            );
        }
    }

    public function setTimesPerWeek()
    {
        $check = DB::table("sets")
            ->select(DB::raw("DISTINCT DATE(updated_at)"))
            ->where("completed", 1)
            ->where("workoutId", $this->id)
            ->where(DB::raw("STR_TO_DATE(CONCAT(YEARWEEK(updated_at), ' monday'), '%X%V %W')"), ">=", DB::raw("STR_TO_DATE(CONCAT(YEARWEEK(CURDATE()), ' monday'), '%X%V %W')"))
            ->count();

        $this->timesPerWeek = $check;
        $this->save();
    }

    public static function AddWorkoutToUser($workoutId, $userId, $author = false, $lock = true)
    {
        $workout = Workouts::find($workoutId);
        $workoutNew = null;

        if ($workout) {
            $workoutNew = $workout->replicate();
            $workoutNew->userId = $userId;
            if ($author) {
                $workoutNew->authorId = $userId;
            }
            $workoutNew->shares = 0;
            $workoutNew->views = 0;
            $workoutNew->lock = $lock ? 1 : 0;
            if (!$lock) {
                $workoutNew->authorId = $userId;
            }
            $workoutNew->availability = "private";
            $workoutNew->timesPerformed = 0;
            $workoutNew->save();

            $workoutNew->resetWorkout();
            $workoutNew->duplicateTemplateFrom($workout);
            $workoutNew->createSets();

            $workout->shares++;
            $workout->save();
        }

        return $workoutNew;
    }

    public static function copyWorkoutsFromTo($fromId, $toId)
    {
        $workouts = Workouts::where("userId", $fromId)->get();

        foreach ($workouts as $workout) {
            if (!empty($workout->master) && $workout->master != 0) {
                if (Workouts::where("userId", $toId)->where("master", $workout->master)->count() == 0) {
                    Workouts::AddWorkoutToUser($workout->id, $toId);
                }
            }
        }
    }
}
