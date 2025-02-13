<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use App\Models\Tasks;
use App\Models\Feeds;
use DateTime;

class TasksController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index($request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        $date = new DateTime('today');

        if ($request->get("userId")) {
            return View::make("widgets.base.tasks")
                ->with("tasksOld", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->take($this->pageSize)->get())
                ->with("tasksToday", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->take($this->pageSize)->get())
                ->with("tasksNew", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . "23:59:59")->take($this->pageSize)->get())
                ->with("permissions", $permissions)
                ->with("tasksOldTotal", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->count())
                ->with("tasksTodayTotal", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->count())
                ->with("tasksNewTotal", Tasks::where("userId", "=", $user->id)->where("targetId", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . " 23:59:59")->count());
        }

        return View::make("widgets.base.tasks")
            ->with("tasksOld", Tasks::where("userId", "=", $user->id)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->take($this->pageSize)->get())
            ->with("tasksToday", Tasks::where("userId", "=", $user->id)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->take($this->pageSize)->get())
            ->with("tasksNew", Tasks::where("userId", "=", $user->id)->where("dateStart", ">", $date->format("Y-m-d ") . "23:59:59")->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("tasksOldTotal", Tasks::where("userId", "=", $user->id)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->count())
            ->with("tasksTodayTotal", Tasks::where("userId", "=", $user->id)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->count())
            ->with("tasksNewTotal", Tasks::where("userId", "=", $user->id)->where("dateStart", ">", $date->format("Y-m-d ") . " 23:59:59")->count());
    }

    public function indexFull($request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.full.tasks")
            ->with("tasks", Tasks::where("userId", "=", $userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("total", Tasks::where("userId", "=", $userId)->count());
    }

    public function AddEdit($request)
    {
        if ($request->get("id") && $request->get("id") != "") {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function create($request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $permissions = null;
        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) $userId = $request->get("userId");
            if ($permissions["add"]) $userId = $request->get("userId");
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($permissions["add"]) {
            $validation = Tasks::validate($request->all());
            if ($validation->fails()) {
                return $this::responseJsonErrorValidation($validation->messages());
            } else {
                $tasks = new Tasks;
                $tasks->value = $request->get("task");
                $tasks->dateStart = Helper::toDateTime($request->get("dateStart") . " " . $request->get("timeStart"));
                $tasks->userId = Auth::user()->id;
                if ($request->get("appointmentTarget") != "") {
                    $appointments->targetId = $request->get("appointmentTarget");
                }
                $tasks->name = $request->get("searchAppointmentTarget");
                $tasks->userId = $user->id;
                $tasks->value = $request->get("value");
                $tasks->type = $request->get("type");
                $tasks->save();
                if ($tasks->type == "reminder") {
                    Feeds::insertFeed("NewReminder", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                    return $this::responseJson(Lang::get("messages.ReminderAdded"));
                } else {
                    Feeds::insertFeed("NewTask", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                    return $this::responseJson(Lang::get("messages.TaskAdded"));
                }
            }
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function completeTask($request)
    {
        $task = Tasks::find($request->get("task"));
        if ($task) {
            if ($task->completed != "") {
                $task->completed = null;
            } else {
                $task->completed = date("Y-m-d H:i:s");
            }
            $task->save();
            if ($task->type == "reminder") {
                return $this::responseJson(Lang::get("messages.ReminderCompleted"));
            }
            return $this::responseJson(Lang::get("messages.TaskCompleted"));
        }
        return $this::responseJsonError(Lang::get("messages.NotFound"));
    }

    public function destroy($id)
    {
        $obj = Tasks::find($id);
        if (!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            if ($obj->type == "reminder") {
                Feeds::insertFeed("DeleteReminder", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                $obj->delete();
                return $this::responseJson(Lang::get("messages.ReminderDeleted"));
            } else {
                $obj->delete();
                return $this::responseJson(Lang::get("messages.TaskDeleted"));
            }
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
