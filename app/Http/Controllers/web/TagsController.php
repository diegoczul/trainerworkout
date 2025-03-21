<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use App\Models\Tags;
use App\Models\Workouts;

class TagsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"), "w_tags");
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $datay1 = [];
        $datay2 = [];
        $y1 = [];
        $tags = [];
        $workoutId = "";

        if ($request->get("arrayData") && array_key_exists("workoutId", json_decode($request->get("arrayData"), true))) {
            $arrayData = json_decode($request->get("arrayData"), true);
            $workout = Workouts::find($arrayData["workoutId"]);
            $workoutId = $workout->id;
            if ($workout) {
                $tagsString = $workout->tags;
                $tagsArray = explode(",", $tagsString);
                $tags = Tags::where("userId", $userId)->whereIn("name", $tagsArray)->orderBy('name', 'ASC')->get();
            }
        } else {
            $tags = Tags::where("userId", $userId)->orderBy('name', 'ASC')->get();
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return view("widgets.base.tags", compact('tags', 'workoutId', 'permissions', 'user'))
            ->with("total", count($tags));
    }

    public function indexWorkout(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"), "w_tags");
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $datay1 = [];
        $datay2 = [];
        $y1 = [];
        $tags = [];
        $workoutId = "";

        if ($request->get("arrayData") && array_key_exists("workoutId", json_decode($request->get("arrayData"), true))) {
            $arrayData = json_decode($request->get("arrayData"), true);
            $workout = Workouts::find($arrayData["workoutId"]);
            if ($workout) {
                $workoutId = $workout->id;
                $tagsString = $workout->tags;
                $tagsArray = explode(",", $tagsString);
                $tags = Tags::where("userId", $userId)->whereIn("name", $tagsArray)->orderBy('name', 'ASC')->get();
            }
        } else {
            $tags = Tags::where("userId", $userId)->orderBy('name', 'ASC')->get();
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return view("widgets.base.tagsWorkout", compact('tags', 'workoutId', 'permissions', 'user'))
            ->with("total", count($tags));
    }

    public function indexFull(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
        }

        $tags = Tags::where("userId", $userId)->orderBy('name', 'ASC')->get();

        if ($request->get("pageSize")) {
            $this->pageSizeFull = $request->get("pageSize") + $this->pageSizeFull;
        }

        return view("widgets.full.tags")
            ->with("tags", Tags::where("userId", $userId)->orderBy('name', 'ASC')->take($this->pageSizeFull)->get())
            ->with(compact('permissions', 'user'))
            ->with("total", Tags::where("userId", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        if ($request->get("id")) {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $permissions = null;
        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"] || $permissions["add"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($permissions["add"]) {
            $validation = Tags::validate($request->all());
            if ($validation->fails()) {
                if ($request->ajax()) {
                    return $this::responseJsonErrorValidation($validation->messages());
                }else{
                    return redirect()->back()->withErrors($validation->messages());
                }
            } else {
                if ($request->filled("tagNameTag")) {
                    $tagNameTag = new Tags;
                    $tagNameTag->userId = Auth::user()->id;
                    $tagNameTag->name = $request->get("tagNameTag");
                    $tagNameTag->type = "tag";
                    if (Tags::where("userId", $userId)->where("name", $tagNameTag->name)->where("type", $tagNameTag->type)->count() == 0) {
                        $tagNameTag->save();
                    }
                }
                if ($request->filled("tagNameClient")) {
                    $tagNameClient = new Tags;
                    $tagNameClient->userId = Auth::user()->id;
                    $tagNameClient->name = $request->get("tagNameClient");
                    $tagNameClient->type = "user";
                    if (Tags::where("userId", $userId)->where("name", $tagNameClient->name)->where("type", $tagNameClient->type)->count() == 0) {
                        $tagNameClient->save();
                    }
                }
                if($request->filled("tagName")) {
                    $tagName = new Tags;
                    $tagName->userId = Auth::user()->id;
                    $tagName->name = $request->get("tagName");
                    $tagName->type = "tag";
                    if (Tags::where("userId", $userId)->where("name", $tagName->name)->where("type", $tagName->type)->count() == 0) {
                        $tagName->save();
                    }
                }


                if ($request->get("workoutId")) {
                    $workout = Workouts::find($request->get("workoutId"));
                    if ($workout) {
                        $tags = $workout->tags;
                        $tagsArray = explode(",", $tags);

                        if ($request->filled("tagNameTag")) {
                            Event::dispatch('createTag', [Auth::user(), $workout->name, $tagNameTag->name]);
                            array_push($tagsArray, $tagNameTag->name);
                        }
                        if ($request->filled("tagNameClient")) {
                            Event::dispatch('createTag', [Auth::user(), $workout->name, $tagNameClient->name]);
                            array_push($tagsArray, $tagNameClient->name);
                        }
                        if ($request->filled("tagName")) {
                            Event::dispatch('createTag', [Auth::user(), $workout->name, $tagName->name]);
                            array_push($tagsArray, $tagName->name);
                        }

                        $tags = implode(",", $tagsArray);
                        $workout->tags = $tags;
                        $workout->save();
                    }
                }

                if ($request->ajax()) {
                    return $this::responseJson(Lang::get("messages.TagsAdded"));
                }else{
                    return redirect()->back()->with("message", Lang::get("messages.TagsAdded"));
                }
            }
        } else {
            if ($request->ajax()){
                return $this::responseJsonError(Lang::get("messages.Permissions"));
            }else{
                return redirect()->back()->with("message", Lang::get("messages.Permissions"));
            }
        }
    }

    public function store()
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
        $obj = Tags::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        Event::dispatch('destroyTag', [Auth::user(), $obj->name]);

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $workouts = Workouts::where(function ($query) use ($obj) {
                $query->orWhere("tags", "like", "%" . $obj->name . ",%");
                $query->orWhere("tags", "like", "%" . "," . $obj->name . "%");
                $query->orWhere("tags", "like", "%" . "," . $obj->name . ",%");
            })->get();

            foreach ($workouts as $workout) {
                $workout->tags = str_replace("," . $obj->name . ",", "", $workout->tags);
                $workout->tags = str_replace($obj->name . ",", "", $workout->tags);
                $workout->tags = str_replace("," . $obj->name, "", $workout->tags);
                $workout->save();
            }

            $obj->delete();
            return $this::responseJson(Lang::get("messages.TagsDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function destroyTagWorkout(Request $request)
    {
        $id = $request->get("tag");
        $workoutId = $request->get("workoutId");
        $workout = Workouts::find($workoutId);
        $tag = Tags::find($id);

        Event::dispatch('removeTagWorkout', [Auth::user(), $workout->name, $tag->name]);

        if ($workout) {
            $tags = $workout->tags;
            $tagsArray = explode(",", $tags);
            $newTags = array_filter(array_diff(array_map('strtolower', $tagsArray), [strtolower($tag->name)]));
            $tags = implode(",", $newTags);
            $workout->tags = $tags;
            $workout->save();
            return $this::responseJson(Lang::get("messages.TagsDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }
    }
}
