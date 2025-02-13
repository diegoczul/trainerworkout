<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\Availabilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tasks;
use App\Models\Appointments;
use App\Models\Feeds;
use DateTime;

class AvailabilitiesController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $permissions = null;
        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;
        $date = new DateTime('today');

        return view("widgets.base.tasks")
            ->with("tasksOld", Tasks::where("userId", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->take($this->pageSize)->get())
            ->with("tasksToday", Tasks::where("userId", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->take($this->pageSize)->get())
            ->with("tasksNew", Tasks::where("userId", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . "23:59:59")->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("tasksOldTotal", Tasks::where("userId", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->count())
            ->with("tasksTodayTotal", Tasks::where("userId", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->count())
            ->with("tasksNewTotal", Tasks::where("userId", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . " 23:59:59")->count());
    }

    public function addEntry($start, $end)
    {
        return view("popups.calendar")
            ->with("start", $start)
            ->with("end", $end);
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $permissions = null;
        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }
        if ($request->has("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

        return view("widgets.full.tasks")
            ->with("tasks", Tasks::where("userId", $userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", Tasks::where("userId", $userId)->count());
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

        if ($request->get("type") == "Appointment") {
            $appointments = new Appointments;
            $appointments->dateStart = Helper::toDateTime($request->get("dateStart") . " " . $request->get("timeStart"));
            $appointments->dateEnd = Helper::toDateTime($request->get("dateEnd") . " " . $request->get("timeEnd"));
            if ($request->get("appointmentTarget") != "") {
                $appointments->targetId = $request->get("appointmentTarget");
            }
            $appointments->name = $request->get("searchAppointmentTarget");
            $appointments->userId = $user->id;
            $appointments->save();
            return $this->responseJson(__("messages.AppointmentAdded"));
        }
        if ($request->get("type") == "Availability") {
            $availability = new Availabilities;
            $availability->title = $request->get("appointment");
            $availability->description = $request->get("task");
            $availability->dateStart = Helper::toDateTime($request->get("dateStart") . " " . $request->get("timeStart"));
            $availability->dateEnd = Helper::toDateTime($request->get("dateEnd") . " " . $request->get("timeEnd"));
            if ($request->get("appointmentTarget") != "") {
                $availability->targetId = $request->get("appointmentTarget");
            }
            $availability->name = $request->get("searchAppointmentTarget");
            $availability->userId = $user->id;
            $availability->save();
            Feeds::insertFeed("NewAvailailibility", Auth::id(), Auth::user()->firstName, Auth::user()->lastName);
            return $this->responseJson(__("messages.AvailabilityAdded"));
        }
    }

    public function updateEvent(Request $request)
    {
        if ($request->get("type") == "Appointment") {
            $appointment = Appointments::find($request->get("eventId"));
            if ($appointment) {
                $appointment->dateStart = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("start"))));
                $appointment->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("end"))));
                $appointment->save();
                return $this->responseJson(__("messages.AppointmentUpdated"));
            }
        }
        if ($request->get("type") == "Reminder") {
            $task = Tasks::find($request->get("eventId"));
            if ($task) {
                $task->dateStart = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("start"))));
                $task->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("end"))));
                $task->save();
                return $this->responseJson(__("messages.ReminderUpdated"));
            }
        }
        if ($request->get("type") == "Task") {
            $task = Tasks::find($request->get("eventId"));
            if ($task) {
                $task->dateStart = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("start"))));
                $task->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("end"))));
                $task->save();
                return $this->responseJson(__("messages.TaskUpdated"));
            }
        }
        if ($request->get("type") == "Availability") {
            $availability = Availabilities::find($request->get("eventId"));
            if ($availability) {
                $availability->dateStart = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("start"))));
                $availability->dateEnd = Helper::toDateTime(date("Y-m-d H:i:s", strtotime($request->get("end"))));
                $availability->save();
                return $this->responseJson(__("messages.AvailabiltiyUpdated"));
            }
        }
    }

    public function getCalendar()
    {
        $user = Auth::user();

        $allEntries = [];

        $appointments = Appointments::where("userId", $user->id)->get();
        $tasks = Tasks::where("userId", $user->id)->where("type", "Task")->get();
        $reminders = Tasks::where("userId", $user->id)->where("type", "Reminder")->get();
        $availabilities = Availabilities::where("userId", $user->id)->get();

        foreach ($appointments as $appointment) {
            $name = "";
            if ($appointment->name != "") $name = " " . $appointment->name;
            $entry = [
                "start" => $appointment->dateStart,
                "end" => $appointment->dateEnd,
                "title" => $appointment->appointment . $name,
                "color" => "#0066cc",
                "type" => "Appointment",
                "eventId" => $appointment->id
            ];
            array_push($allEntries, $entry);
        }

        foreach ($tasks as $task) {
            $name = "";
            if ($task->name != "") $name = " " . $task->name;
            $entry = [
                "start" => $task->dateStart,
                "end" => $task->dateEnd,
                "title" => $task->value . $name,
                "color" => "#999999",
                "type" => "Task",
                "eventId" => $task->id
            ];
            array_push($allEntries, $entry);
        }

        foreach ($reminders as $reminder) {
            $name = "";
            if ($task->reminder != "") $name = " " . $task->reminder;
            $entry = [
                "start" => $reminder->dateStart,
                "end" => $reminder->dateEnd,
                "title" => $reminder->value . $name,
                "color" => "#ffa000",
                "type" => "Reminder",
                "eventId" => $reminder->id
            ];
            array_push($allEntries, $entry);
        }

        foreach ($availabilities as $availability) {
            $name = "";
            $entry = [
                "start" => $availability->dateStart,
                "end" => $availability->dateEnd,
                "title" => $availability->description,
                "color" => "#551a8b",
                "type" => "Availability",
                "eventId" => $availability->id
            ];
            array_push($allEntries, $entry);
        }

        return $this->responseJson($allEntries);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function completeTask(Request $request)
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
                return $this->responseJson(__("messages.ReminderCompleted"));
            }
            return $this->responseJson(__("messages.TaskCompleted"));
        }
        return $this->responseJsonError(__("messages.NotFound"));
    }

    public function destroy($id)
    {
        $obj = Tasks::find($id);
        if (!$obj) return $this->responseJsonError(__("messages.NotFound"));

        if ($this->checkPermissions($obj->userId, Auth::id())) {
            if ($obj->type == "reminder") {
                $obj->delete();
                return $this->responseJson(__("messages.ReminderDeleted"));
            } else {
                $obj->delete();
                return $this->responseJson(__("messages.TaskDeleted"));
            }
        } else {
            return $this->responseJsonError(__("messages.Permissions"));
        }
    }
}
