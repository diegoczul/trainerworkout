<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Http\Libraries\Messages;
use App\Models\ExercisesBodyGroups;
use App\Models\ExercisesImages;
use App\Models\Feeds;
use App\Models\TemplateSets;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exercises;
use App\Models\ExercisesEquipments;
use App\Models\ExercisesUser;
use App\Models\Equipments;
use App\Models\BodyGroups;
use App\Models\ExercisesTypes;
use App\Models\Tags;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ExercisesController extends BaseController
{
    public $pageSize = 40;
    public $searchSize = 40;
    public $pageSizeFull = 40;

    public function indexAdd(Request $request)
    {
        $userId = Auth::id();

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        $tags = Tags::where("userId", $userId)->get();

        return View::make(Helper::userTypeFolder(Auth::user()->userType) . ".addExercise")->with("bodygroups", Exercises::getBodyGroupsList())->with("equipments", Equipments::orderBy("name")->pluck("name", "id"))->with("tags", $tags)->with("bodyGroups", BodyGroups::select("id", "name")->orderBy("name")->get())->with("equipments", Equipments::select("id", "name")->orderBy("name")->get())->with("exercisesTypes", ExercisesTypes::select("id", "name")->orderBy("name")->get())->with("total", Exercises::where("userId", $userId)->count());
    }

    public function editExercise(Request $request, $id)
    {
        $exercise = Exercises::find($id);
        $equipmentsSelected = ExercisesEquipments::where("exerciseId", $id)->where("type", "required")->pluck("equipmentId");
        $equipmentsSelectedOptional = ExercisesEquipments::where("exerciseId", $id)->where("type", "optional")->pluck("equipmentId");

        if ($exercise) {
            $userId = Auth::id();

            if ($request->has("pageSize")) {
                $this->pageSize = $request->get("pageSize") + $this->pageSize;
            }

            $tags = Tags::where("userId", $userId)->get();

            return View::make(Helper::userTypeFolder(Auth::user()->userType) . ".editExercise")->with("bodygroups", Exercises::getBodyGroupsList())->with("equipments", Equipments::orderBy("name")->pluck("name", "id"))->with("tags", $tags)->with("exercise", $exercise)->with("equipmentsSelected", $equipmentsSelected)->with("equipmentsSelectedOptional", $equipmentsSelectedOptional)->with("bodyGroups", BodyGroups::select("id", "name")->orderBy("name")->get())->with("exercisesTypes", ExercisesTypes::select("id", "name")->orderBy("name")->get())->with("total", Exercises::where("userId", $userId)->count());
        }

        return redirect()->back()->withErrors(Lang::get("messages.Oops"));
    }

    public function clearAttribute(Request $request)
    {
        $id = $request->get("id");
        $attribute = $request->get("attribute");

        $exercise = Exercises::find($id);

        if ($exercise) {
            if ($attribute == "image" || $attribute == "video" || $attribute == "image2") {
                $exercise->removeFile($attribute);
                return response()->json(Lang::get("content.Deleted"));
            }
        }
    }

    public function indexExercises(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions($userId, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions($userId, $request->get("userId"));
        }

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("trainee.exercises")->with("exercises", Exercises::where("userId", $userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())->with("permissions", $permissions)->with("user", $user)->with("total", Exercises::where("userId", $userId)->count());
    }

    public function addToFavorites(Request $request)
    {
        $id = $request->get("id");
        $equipmentId = $request->get("equipmentId");
        $userId = Auth::id();
        $action = "added";

        $ex = Exercises::find($id);
        $exercise = ExercisesUser::where("userId", $userId)->where("locale", app()->getLocale())->where("exerciseId", $id)->where("equipmentId", $equipmentId)->first();

        if ($id && $ex) {
            if (!$exercise) {
                foreach (['en', 'fr'] as $locale) {
                    $exercise = new ExercisesUser;
                    $exercise->name = $ex->translate($locale)->name;
                    $exercise->locale = $locale;
                    $exercise->userId = $userId;
                    $exercise->equipmentId = $equipmentId;
                    $exercise->exerciseId = $id;
                    $exercise->favorite = 1;
                    $exercise->save();
                }
            } else {
                $exercise->favorite = $exercise->favorite == 1 ? null : 1;
                $action = $exercise->favorite == 1 ? "added" : "removed";
                $exercise->save();
            }
        }

        return response()->json(Messages::showControlPanel("AddedToFavorites"));
    }

    public function indexAddInWorkout(Request $request)
    {
        $userId = Auth::id();

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("popups.addExerciseInWorkout")->with("bodygroups", Exercises::getBodyGroupsList())->with("equipments", Equipments::orderBy("name")->pluck("name", "id"))->with("total", Exercises::where("userId", $userId)->count());
    }

    public function indexExercisesTrainer(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions($userId, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions($userId, $request->get("userId"));
        }

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("trainer.exercises")
            ->with("exercises", Exercises::where("userId", $userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("bodyGroups", BodyGroups::select("id", "name")->orderBy("name")->get())
            ->with("bodygroups", BodyGroups::orderBy("name")->pluck("name", "id"))
            ->with("equipments", Equipments::select("id", "name")->orderBy("name")->get())
            ->with("equipmentsList", Equipments::orderBy("name")->pluck("name", "id"))
            ->with("exercisesTypes", ExercisesTypes::select("id", "name")->orderBy("name")->get())
            ->with("total", Exercises::where("userId", $userId)->count());
    }

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
        }

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        return view("widgets.base.exercises", ["exercises" => Exercises::where("userId", $userId)->take($this->pageSize)->get(), "permissions" => $permissions, "total" => Exercises::where("userId", $userId)->count()]);
    }

    public function managerExercises(Request $request, $search)
    {
        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        return view("ControlPanel.manageExercises", ["bodygroups" => Exercises::getBodyGroupsList(), "exercises" => Exercises::searchExercises($search)->take(200)->get(), "total" => 200]);
    }

    public function searchExercise(Request $request)
    {
        if ($request->has("pageSize")) {
            $this->searchSize += $request->get("pageSize");
        }

        Exercises::searchExercises($request->get("search"), $this->searchSize, $request->get("filters"));

        return $this->responseJson(["data" => Exercises::searchExercises($request->get("search"), $this->searchSize, $request->get("filters")), "total" => $this->searchSize + $request->get("pageSize")]);
    }

    public function indexMail(Request $request)
    {
        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        return view("widgets.full.mail");
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
        }

        if ($request->get("search") == "") {
            $exercises = Exercises::where("userId", $userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get();
            $total = Exercises::where("userId", $userId)->count();
        } else {
            $exercises = Exercises::searchExercises($request->get("search"), $this->searchSize, null, true);
            $total = $this->searchSize + $request->get("pageSize");
        }

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        return view("widgets.full.exercises", ["permissions" => $permissions, "exercises" => $exercises, "total" => $total]);
    }

    public function indexFullTrainer(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('userId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('userId'));
        }

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("widgets.full.exercises")->with("permissions", $permissions)->with("exercises", Exercises::where("userId", $userId)->orderBy('created_at', 'DESC')->take($this->pageSize)->get())->with("total", Exercises::where("userId", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        return $request->filled('id') ? $this->update($request,$request->get('id')) : $this->create($request);
    }

    public function AddEditInWorkout(Request $request)
    {
        return $request->filled('id') ? $this->update($request->get('id'), "async") : $this->create($request,"async");
    }

    public function create(Request $request,$requestType = "")
    {
        $user = Auth::user();

        $validation = Exercises::validate($request->all());

        if ($validation->fails()) {
            if ($requestType == "") {
                return Redirect::back()->withErrors($validation->messages()->first())->withInput();
            } else {
                return $this::responseJsonError($validation->messages()->first());
            }
        } else {
            $exercise = new Exercises;
            $exercise->name = ucfirst($request->get("name"));
            $exercise->description = $request->get("description");
            $exercise->bodygroupId = $request->get("bodygroup");
            $exercise->youtube = Helper::extractYoutubeTag($request->get("youtube"));
            $exercise->nameEngine = $request->get("nameEngine");
            $exercise->type = $request->has("publicLicense") ? "public" : "private";
            $exercise->equipmentRequired = $request->has("equipmentRequired") ? 1 : 0;

            $exercise->userId = $user->id;
            $exercise->authorId = $user->id;
            Helper::checkUserFolder($user->id);

            if ($request->has("removeGreenScreen")) {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImageGreenScreen($request->file("image1"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImageGreenScreen($request->file("image2"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            } else {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImage($request->file("image1"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImage($request->file("image2"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            }

            if ($request->hasFile("video")) {
                $video = Helper::uploadFile($request->file("video"), $user->getPath() . Config::get("constants.videosExercisesPath") . "/" . $exercise->id);
                $exercise->video = $video;
            }

            $exercise->save();

            $this->saveEquipments($request, $exercise);

            $this->saveExerciseImages($request, $exercise);

            if ($exercise->getTranslation("en", false) == "") {
                $ex = $exercise->translateOrNew("en");
                $ex->name = ucfirst($request->get("name"));
                $ex->exercises_id = $exercise->id;
                $ex->created_at = now();
                $ex->save();
            }

            Event::dispatch('addedAnExercise', [$user, $exercise->name]);

            return $this->handleExerciseRedirection($requestType, $user, $exercise);
        }
    }

    private function saveEquipments($request, $exercise)
    {
        foreach (['equipment' => 'required', 'equipmentOptional' => 'optional', 'equipmentHidden' => 'hidden'] as $key => $type) {
            if ($request->has($key)) {
                $equipments = is_array($request->get($key)) ? $request->get($key) : [$request->get($key)];
                foreach ($equipments as $equi) {
                    ExercisesEquipments::updateOrCreate([
                        'exerciseId' => $exercise->id,
                        'equipmentId' => $equi,
                        'type' => $type,
                    ]);
                }
            }
        }
    }

    private function saveExerciseImages($request, $exercise)
    {
        for ($i = 3; $i <= 6; $i++) {
            if ($request->hasFile("image$i")) {
                $exerciseImage = new ExercisesImages();
                $exerciseImage->userId = null;
                $exerciseImage->exerciseId = $exercise->id;
                $exerciseImage->availability = "public";
                $exerciseImage->save();

                $images = Helper::saveImage($request->file("image$i"), Config::get("constants.moreExercises"));
                $exerciseImage->image = $images["image"];
                $exerciseImage->thumb = $images["thumb"];
                $exerciseImage->save();
            }
        }
    }

    private function handleExerciseRedirection($requestType, $user, $exercise)
    {
        if (Auth::check()) {
            Feeds::insertFeed("NewExercise", $user->id, $user->firstName, $user->lastName);

            if ($requestType == "") {
                return $this->redirectBasedOnUserType($user);
            } else {
                return $this::responseJson(Lang::get("messages.ExerciseAdded"));
            }
        } else {
            return $this::responseJson(Lang::get("messages.ExerciseAdded"));
        }
    }

    private function redirectBasedOnUserType($user)
    {
        if ($user->userType == "Trainer") {
            return Redirect::route("ExercisesHomeTrainer")->with("message", Lang::get("messages.ExerciseAdded"));
        }

        return Redirect::route("ExercisesHomeTrainee")->with("message", Lang::get("messages.ExerciseAdded"));
    }

    public function store()
    {
        //
    }

    public function show($id, $name = "")
    {
        $exercise = Exercises::with("equipments")->with("equipmentsOptional")->with("exercisesTypes")->find($id);

        if ($exercise) {
            if (Auth::check()) {
                return view(strtolower(Auth::user()->userType) . ".exercise")->with("exercise", $exercise);
            } else {
                return view("visitor.exercise")->with("exercise", $exercise);
            }
        } else {
            return redirect()->route("Trainee", Helper::userHome())->with("error", Lang::get("messages.NotFound"));
        }
    }

    public function APIShow($id)
    {
        $exercise = Exercises::find($id);

        return response()->json($exercise);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $requestType = "";

        $validation = Exercises::validate($request->all());
        if ($validation->fails()) {
            if ($requestType == "") {
                return back()->withErrors($validation->messages()->first())->withInput();
            } else {
                return $this->responseJsonError($validation->messages()->first());
            }
        } else {
            $exercise = Exercises::find($id);
            $exercise->name = ucfirst($request->get("name"));
            $exercise->description = $request->get("description");
            $exercise->bodygroupId = $request->get("bodygroup");
            $exercise->youtube = Helper::extractYoutubeTag($request->get("youtube"));
            $exercise->nameEngine = $request->get("nameEngine");
            $exercise->type = $request->has("publicLicense") ? "public" : "private";
            $exercise->equipmentRequired = $request->has("equipmentRequired") ? 1 : 0;
            $exercise->userId = Auth::id();
            $exercise->authorId = Auth::id();
            Helper::checkUserFolder($user->id);

            if ($request->has("removeGreenScreen")) {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImageGreenScreen($request->file("image1"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImageGreenScreen($request->file("image2"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            } else {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImage($request->file("image1"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImage($request->file("image2"), $user->getPath() . Config::get("constants.exercisesPath") . "/" . $exercise->id);
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            }

            if ($request->hasFile("video")) {
                $video = Helper::uploadFile($request->file("video"), $user->getPath() . Config::get("constants.videosExercisesPath") . "/" . $exercise->id);
                $exercise->video = $video;
            }

            $exercise->save();

            if ($request->has("equipment")) {
                $this->saveEquipments($request, $exercise);
                $exercise->equipmentRequired = 1;
            }

            if ($request->has("equipmentOptional")) {
                $this->saveEquipments($request, $exercise);
            }

            if ($request->has("equipmentHidden")) {
                $this->saveEquipments($request, $exercise);
            }

            $this->saveExerciseImages($request, $exercise->id);

            $exercise->save();

            if ($exercise->translateOrNew("en")) {
                $ex = $exercise->translateOrNew('en');
                $ex->name = ucfirst($request->get('name'));
                $ex->exercises_id = $exercise->id;
                $ex->created_at = now();
                $ex->save();
            }

            Event::dispatch('addedAnExercise', [Auth::user(), $exercise->name]);

            if (Auth::check()) {
                Feeds::insertFeed("NewExercise", $user->id, $user->firstName, $user->lastName);
                if ($requestType == "") {
                    return redirect()->route("ExercisesHomeTrainer")->with("message", Lang::get("messages.ExerciseAdded"));
                }
                return redirect()->route("ExercisesHomeTrainee")->with("message", Lang::get("messages.ExerciseAdded"));
            }

            return response()->json(Lang::get("messages.ExerciseAdded"));
        }
    }

    public function destroy($id)
    {
        $obj = Exercises::find($id);
        if (!$obj) return response()->json(["error" => Lang::get("messages.NotFound")]);

        Event::dispatch('deletedAnExercise', [Auth::user(), $obj->name]);
        $obj->delete();

        return response()->json(Lang::get("messages.ExerciseDeleted"));
    }

    public function switchPictures()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);

        if ($obj) {
            $image = $obj->image2;
            $image2 = $obj->image;
            $thumb = $obj->thumb2;
            $thumb2 = $obj->thumb;
            $obj->image = $image;
            $obj->image2 = $image2;
            $obj->thumb = $thumb;
            $obj->thumb2 = $thumb2;
            $obj->save();

            return $this::responseJson(Lang::get("messages.ImageSwitched"));
        }
    }

    public function rotateRight()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);
        $images = [true, true];
        $imageNumber = request()->input("imageNumber");
        if ($imageNumber !== null && $imageNumber !== "") {
            $images = [false, false];
            $images[$imageNumber - 1] = true;
        }

        if ($obj) {
            if (file_exists($obj->image) && $images[0]) {
                $image = Image::make($obj->image);
                $image->rotate(-90)->save();
                $thumb = Image::make($obj->thumb);
                $thumb->rotate(-90)->save();
            }
            if (file_exists($obj->image2) && $images[1]) {
                $image2 = Image::make($obj->image2);
                $image2->rotate(-90)->save();
                $thumb2 = Image::make($obj->thumb2);
                $thumb2->rotate(-90)->save();
            }

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateLeft()
    {
        $id = request()->input("id");
        $images = [true, true];
        $imageNumber = request()->input("imageNumber");
        if ($imageNumber !== null && $imageNumber !== "") {
            $images = [false, false];
            $images[$imageNumber - 1] = true;
        }
        $obj = Exercises::find($id);

        if ($obj) {
            if (file_exists($obj->image) && $images[0]) {
                $image = Image::make($obj->image);
                $image->rotate(90)->save();
                $thumb = Image::make($obj->thumb);
                $thumb->rotate(90)->save();
            }
            if (file_exists($obj->image2) && $images[1]) {
                $image2 = Image::make($obj->image2);
                $image2->rotate(90)->save();
                $thumb2 = Image::make($obj->thumb2);
                $thumb2->rotate(90)->save();
            }

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateRight1()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(-90)->save();
            $thumb = Image::make($obj->thumb);
            $thumb->rotate(-90)->save();

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateLeft1()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(90)->save();
            $thumb = Image::make($obj->thumb);
            $thumb->rotate(90)->save();

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateRight2()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);

        if ($obj && file_exists($obj->image2)) {
            $image2 = Image::make($obj->image2);
            $image2->rotate(-90)->save();
            $thumb2 = Image::make($obj->thumb2);
            $thumb2->rotate(-90)->save();

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateLeft2()
    {
        $id = request()->input("id");
        $obj = Exercises::find($id);

        if ($obj && file_exists($obj->image2)) {
            $image2 = Image::make($obj->image2);
            $image2->rotate(90)->save();
            $thumb2 = Image::make($obj->thumb2);
            $thumb2->rotate(90)->save();

            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function removeImage()
    {
        $id = request()->input("id");
        $image = Exercises::find($id);

        if (request()->input("image") == 1) {
            File::delete($image->image);
            File::delete($image->thumb);
            $image->image = null;
            $image->thumb = null;
        }
        if (request()->input("image") == 2) {
            File::delete($image->image2);
            File::delete($image->thumb2);
            $image->image2 = null;
            $image->thumb2 = null;
        }

        $image->save();

        return $this::responseJson(Lang::get("messages.ImageRemoved"));
    }

    public function _index()
    {
        return view('ControlPanel.Exercises')->with("bodygroups", Exercises::getBodyGroupsList())->with("exercisesTypes", ExercisesTypes::orderBy("name", "ASC")->pluck("name", "id"))->with("equipments", Equipments::orderBy("name", "ASC")->pluck("name", "id"))->with("users", Users::select(DB::raw("concat('id: ', id, ' - ', firstName, ' ', lastName) as name"), "id")->orderBy("firstName", "ASC")->orderBy("lastName", "ASC")->pluck("name", "id"));
    }

    public function _ApiList(Request $request)
    {
        $response = Exercises::with(["bodygroup", "exercisesTypes", "bodygroupsOptional", "equipments", "equipmentsOptional", "user", "author"])->orderBy("name", "ASC")->latest();
        return DataTables::eloquent($response)
            ->addIndexColumn()
            ->make(true);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->has("hiddenId") && $request->get("hiddenId") != "") {
            return $this->_update($request->get("hiddenId"),$request);
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = Exercises::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $exercise = new Exercises;
            $exercise->name = $request->get("name");
            $exercise->nameEngine = $request->get("nameEngine");
            $exercise->bodygroupId = $request->get("bodygroupId");
            $exercise->userId = $request->get("userId");
            $exercise->authorId = $request->get("authorId");
            $exercise->views = $request->get("views");
            $exercise->video = $request->get("video");
            $exercise->type = $request->get("type");
            $exercise->youtube = $request->get("youtube");
            $exercise->description = $request->get("description");
            $exercise->used = $request->get("used");
            $exercise->equipmentRequired = $request->has("equipmentRequired") ? 1 : 0;

            if ($request->has("removeGreenScreen")) {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImageGreenScreen($request->file("image1"), config("constants.moreExercises"), $request->get("light"), $request->get("modulation"), $request->get("feather"), $request->get("algo"), $request->get("replacer"), $request->get("color1"), $request->get("color2"));
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImageGreenScreen($request->file("image2"), config("constants.moreExercises"), $request->get("light"), $request->get("modulation"), $request->get("feather"), $request->get("algo"), $request->get("replacer"), $request->get("color1"), $request->get("color2"));
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            } else {
                if ($request->hasFile("image1")) {
                    $images = Helper::saveImage($request->file("image1"), config("constants.moreExercises"));
                    $exercise->image = $images["image"];
                    $exercise->thumb = $images["thumb"];
                }
                if ($request->hasFile("image2")) {
                    $images = Helper::saveImage($request->file("image2"), config("constants.moreExercises"));
                    $exercise->image2 = $images["image"];
                    $exercise->thumb2 = $images["thumb"];
                }
            }

            $exercise->save();

            Exercises::where("id", $exercise->id)->update(["name" => $request->get("name"), "nameEngine" => $request->get("nameEngine")]);

            if ($exercise->getTranslation("en", false) == "") {
                $ex = $exercise->translateOrNew("en");
                $ex->name = ucfirst($request->get("name"));
                $ex->exercises_id = $exercise->id;
                $ex->created_at = now();
                $ex->save();
            }

            $this->updateExerciseEquipments($exercise->id,$request);

            return $this::responseJson(Messages::showControlPanel("ExerciseCreated"));
        }
    }

    public function _show($exercise)
    {
        return Exercises::with(['equipments', 'equipmentsOptional', 'equipmentsHidden', 'bodygroupsOptional', 'exercisesTypes'])->find($exercise);
    }

    public function _update($id, Request $request)
    {
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = Exercises::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $exercise = Exercises::find($id);
        $exercise->name = $request->get('name');
        $exercise->nameEngine = $request->get('nameEngine');
        $exercise->bodygroupId = $request->get('bodygroupId');
        $exercise->userId = $request->get('userId');
        $exercise->authorId = $request->get('authorId');
        $exercise->views = $request->get('views');
        $exercise->video = $request->get('video');
        $exercise->type = $request->get('type');
        $exercise->youtube = $request->get('youtube');
        $exercise->description = $request->get('description');
        $exercise->used = $request->get('used');
        $exercise->equipmentRequired = $request->has('equipmentRequired') ? 1 : 0;

        if ($request->has('removeGreenScreen')) {
            if ($request->hasFile('image1')) {
                $images = Helper::saveImageGreenScreen($request->file('image1'), config('constants.moreExercises'), $request->get('light'), $request->get('modulation'), $request->get('feather'), $request->get('algo'), $request->get('replacer'), $request->get('color1'), $request->get('color2'));
                $exercise->image = $images['image'];
                $exercise->thumb = $images['thumb'];
            }
            if ($request->hasFile('image2')) {
                $images = Helper::saveImageGreenScreen($request->file('image2'), config('constants.moreExercises'), $request->get('light'), $request->get('modulation'), $request->get('feather'), $request->get('algo'), $request->get('replacer'), $request->get('color1'), $request->get('color2'));
                $exercise->image2 = $images['image'];
                $exercise->thumb2 = $images['thumb'];
            }
        } else {
            if ($request->hasFile('image1')) {
                $images = Helper::saveImage($request->file('image1'), config('constants.moreExercises'));
                $exercise->image = $images['image'];
                $exercise->thumb = $images['thumb'];
            }
            if ($request->hasFile('image2')) {
                $images = Helper::saveImage($request->file('image2'), config('constants.moreExercises'));
                $exercise->image2 = $images['image'];
                $exercise->thumb2 = $images['thumb'];
            }
        }

        if ($request->hasFile('video')) {
            $video = Helper::uploadFile($request->file('video'), config('constants.moreExercises'));
            $exercise->video = $video;
        }

        $exercise->save();

        if ($exercise->getTranslation('en', false) == '') {
            $ex = $exercise->translateOrNew('en');
            $ex->name = ucfirst($request->get('name'));
            $ex->exercises_id = $exercise->id;
            $ex->created_at = now();
            $ex->save();
        }

        $this->updateExerciseEquipments($exercise->id, $request);

        return $this::responseJson(Messages::showControlPanel('ExerciseModified'));
    }

    private function updateExerciseEquipments($exerciseId, $request)
    {
        $equipmentTypes = ['equipment' => 'required', 'equipmentOptional' => 'optional', 'equipmentHidden' => 'hidden'];

        foreach ($equipmentTypes as $inputName => $type) {
            if ($request->has($inputName)) {
                $currentEquipments = ExercisesEquipments::where('exerciseId', $exerciseId)->where('type', $type)->pluck('equipmentId')->toArray();

                $newEquipments = $request->get($inputName);
                $toDelete = array_diff($currentEquipments, $newEquipments);
                $toAdd = array_diff($newEquipments, $currentEquipments);

                ExercisesEquipments::where('exerciseId', $exerciseId)->where('type', $type)->whereIn('equipmentId', $toDelete)->delete();

                foreach ($toAdd as $equipment) {
                    if (!empty($equipment) && $equipment != 0) {
                        ExercisesEquipments::create(['exerciseId' => $exerciseId, 'equipmentId' => $equipment, 'type' => $type,]);
                    }
                }
            } else {
                ExercisesEquipments::where('exerciseId', $exerciseId)->where('type', $type)->delete();
            }
        }

        $this->updateBodyGroups($exerciseId, $request);
    }

    private function updateBodyGroups($exerciseId, $request)
    {
        if ($request->has('bodygroupsOptional')) {
            $currentBodyGroups = ExercisesBodyGroups::where('exerciseId', $exerciseId)->pluck('bodygroupId')->toArray();

            $newBodyGroups = $request->get('bodygroupsOptional');
            $toDelete = array_diff($currentBodyGroups, $newBodyGroups);
            $toAdd = array_diff($newBodyGroups, $currentBodyGroups);

            ExercisesBodyGroups::where('exerciseId', $exerciseId)->whereIn('bodygroupId', $toDelete)->delete();

            foreach ($toAdd as $bodyGroup) {
                if (!empty($bodyGroup) && $bodyGroup != 0) {
                    ExercisesBodyGroups::create(['exerciseId' => $exerciseId, 'bodygroupId' => $bodyGroup,]);
                }
            }
        } else {
            ExercisesBodyGroups::where('exerciseId', $exerciseId)->delete();
        }
    }

    public function _destroy($id)
    {
        $exercise = Exercises::find($id);
        $exercise->delete();

        event('deletedAnExercise', [auth()->user(), $exercise->name]);

        return $this::responseJson(Messages::showControlPanel('ExerciseDeleted'));
    }

    //=======================================================================================================================
    // API
    //======================================================================================================================


    public function APIsearchExercise(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('userId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->has('pageSize')) {
            $this->searchSize = $request->get('pageSize') + $this->searchSize;
        }

        $search = Exercises::searchExercises($request->get('search'), $this->searchSize);

        $data = ['data' => $search, 'permissions' => $permissions, 'total' => count($search), 'status' => 'ok', 'message' => ''];

        return $this->responseJson($data);
    }

    public function API_Exercise_Model(Request $request)
    {
        $userId = Auth::id();
        $exerciseId = -1;
        $permissions = null;

        if ($request->has('exerciseId')) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('exerciseId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
                $exerciseId = $request->get('exerciseId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        $exercise = Exercises::where('id', $exerciseId)->get();
        $exercise['templateSets'] = TemplateSets::where('exerciseId', $exerciseId)->get();
        $exercise['sets'] = [];

        $data = ['data' => $exercise, 'permissions' => $permissions, 'total' => 1, 'status' => 'ok', 'message' => ''];

        return $this->responseJson($data);
    }


    // public function API_Exercise_Model() {
    // 	$userId = Auth::user()->id;
    // 	$exerciseId = -1;
    // 	$permissions = null;
    // 	if(Input::has("exerciseId")){
    // 		$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("exerciseId"));
    // 		if($permissions["view"]){
    // 			$userId 	= Input::get("userId");
    // 			$exerciseId = Input::get("exerciseId");
    // 		}
    // 	} else {
    // 		$permissions = Helper::checkPremissions(Auth::user()->id,null);
    // 	}

    // 	$exercise = Exercises::where("id",$exerciseId)->get();
    // 	$exercise["templateSets"]	= TemplateSets::where("exerciseId", $exerciseId)->get();
    // 	$exercise["sets"] 			= array();

    // 	$data = array();
    // 	$data["data"] = $exercise;
    // 	$data["permissions"] = $permissions;
    // 	$data["total"] = 1;
    // 	$data["status"] = "ok";
    // 	$data["message"] = "";

    // 	return $this->responseJson($data);
    // }


}
