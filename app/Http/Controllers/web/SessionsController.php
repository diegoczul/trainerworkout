<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use App\Models\Users;
use App\Models\TrainerSessions;
use App\Models\Feeds;
use Illuminate\Http\Request;

class SessionsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.base.sessions")
            ->with("sessions", TrainerSessions::where("userId", "=", $userId)->take($this->pageSize)->get())
            ->with("user", $user)
            ->with("permissions", $permissions)
            ->with("total", TrainerSessions::where("userId", "=", $userId)->count());
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.full.sessions")
            ->with("sessions", TrainerSessions::where("userId", "=", $userId)->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", TrainerSessions::where("userId", "=", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        if ($request->has("id") && $request->get("id") != "") {
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
        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $validation = TrainerSessions::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $sessions = new TrainerSessions;
            $sessions->name = $request->get("name");
            $sessions->description = $request->get("description");
            $sessions->price = $request->get("price");
            $sessions->numberOfSessions = $request->get("numberOfSessions");
            $sessions->timePerSession = $request->get("timePerSession");
            $sessions->userId = $user->id;
            $sessions->save();

            Feeds::insertFeed("NewSession", $user->id, $user->firstName, $user->lastName);

            return $this::responseJson(Lang::get("messages.SessionAdded"));
        }
    }

    public function update($id, Request $request)
    {
        //
    }

    public function destroy($id)
    {
        $obj = TrainerSessions::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            // Feeds::insertFeed("SessionDeleted", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            $obj->delete();

            return $this::responseJson(Lang::get("messages.SessionDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
