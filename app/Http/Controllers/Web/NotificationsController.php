<?php

namespace App\Http\Controllers\Web;

use App\Models\Notifications;
use App\Models\Objectives;
use App\Models\Feeds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

class NotificationsController extends BaseController
{
    public $pageSize = 5;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $totalNew = Notifications::where("userId", $userId)
            ->whereNull("viewed")
            ->where(function ($query) {
                $query->orWhere("display", "!=", "feed")
                    ->orWhereNull("display");
            })
            ->orderBy('created_at', 'DESC')
            ->count();

        $totalOld = Notifications::where("userId", $userId)
            ->whereNotNull("viewed")
            ->where(function ($query) {
                $query->orWhere("display", "!=", "feed")
                    ->orWhereNull("display");
            })
            ->orderBy('created_at', 'DESC')
            ->count();

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        $data = json_encode([
            'view' => View::make("notifications.notifications")
                ->with("notificationsNew", Notifications::where("userId", $userId)
                    ->where(function ($query) {
                        $query->orWhere("display", "!=", "feed")
                            ->orWhereNull("display");
                    })
                    ->whereNull("viewed")
                    ->orderBy('created_at', 'DESC')
                    ->get())
                ->with("notificationsOld", Notifications::where("userId", $userId)
                    ->where(function ($query) {
                        $query->orWhere("display", "!=", "feed")
                            ->orWhereNull("display");
                    })
                    ->whereNotNull("viewed")
                    ->orderBy('created_at', 'DESC')
                    ->take($this->pageSize)
                    ->get())
                ->with("totalOld", $totalOld)
                ->with("totalNew", $totalNew)
                ->render(),
            'total' => $totalNew
        ]);

        return $data;
    }

    public function indexOld(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("notifications.notifications")
            ->with("user", $user)
            ->with("notificationsOld", Notifications::where("userId", $userId)
                ->whereNotNull("viewed")
                ->orderBy('created_at', 'DESC')
                ->take($this->pageSize)
                ->get())
            ->with("totalOld", Notifications::where("userId", $userId)
                ->whereNotNull("viewed")
                ->orderBy('created_at', 'DESC')
                ->count());
    }

    public function indexFull(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.full.objectives")
            ->with("user", $user)
            ->with("objectives", Objectives::where("userId", $userId)
                ->orderBy('recordDate', 'ASC')
                ->take($this->pageSize)
                ->get())
            ->with("total", Objectives::where("userId", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        if ($request->has("id") && $request->get("id") != "") {
            return $this->update($request, $request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function readNotifications()
    {
        Notifications::readNotifications(Auth::user()->id);
    }

    public function create(Request $request)
    {
        $validation = Objectives::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $objectives = new Objectives;
            $objectives->objective = $request->get("objective");
            $objectives->measureable = $request->get("measureable");
            $objectives->recordDate = $request->get("dateRecord");
            $objectives->userId = Auth::user()->id;
            $objectives->save();
            Feeds::insertFeed("NewObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            return $this::responseJson(Lang::get("messages.ObjectiveAdded"));
        }
    }

    public function store(Request $request)
    {
        // Method left empty as per original
    }

    public function show($id)
    {
        // Method left empty as per original
    }

    public function edit($id)
    {
        // Method left empty as per original
    }

    public function update(Request $request, $id)
    {
        // Method left empty as per original
    }

    public function destroy($id)
    {
        $obj = Objectives::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            Feeds::insertFeed("DeleteObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            $obj->delete();
            return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
