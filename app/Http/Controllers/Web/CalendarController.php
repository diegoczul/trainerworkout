<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\Calendar;
use App\Models\Feeds;
use App\Models\Workoutsperformances;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarController extends BaseController
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

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        $dateEnd = date('Y-m-d', strtotime('today'));
        $dateStart = date('Y-m-d', strtotime($dateEnd . ' - 1 month'));
        $activities = [];

        if ($request->has("arrayData")) {
            $arrayData = json_decode($request->get("arrayData"), true);
            if (is_array($arrayData)) {
                if (array_key_exists("search", $arrayData) && $arrayData["search"] != "") {
                    $search = $arrayData["search"];
                }
                if (array_key_exists("archive", $arrayData) && $arrayData["archive"] == "true") {
                    $archive = true;
                }
            }
        }

        $days = $this->dateDifference($dateEnd, $dateStart);

        for ($x = 0; $x < $days; $x++) {
            $currentDate = date('Y-m-d', strtotime($dateEnd . ' + 1 day'));
            $activities[$currentDate] = ["performance" => []];
        }

        $performances = Workoutsperformances::where("forTrainer", Auth::id())
            ->where("userId", $userId)
            ->whereNotNull("dateCompleted")
            ->get();

        foreach ($performances as $performance) {
            if ($currentDate <= date($performance->dateCompleted) && date($performance->dateCompleted) < date("Y-m-d", strtotime("+1 day", strtotime($currentDate)))) {
                $key = new DateTime($performance->dateCompleted);
                $key = $key->format('Y-m-d');
                array_push($activities[$currentDate]["performance"], $performance);
            }
        }

        return view("widgets.base.activityCalendar")
            ->with("dateStart", $dateStart)
            ->with("dateEnd", $dateEnd)
            ->with("activities", $activities)
            ->with("permissions", $permissions)
            ->with("user", $user);
    }

    function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $permissions = null;
        $default = true;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        $dateEnd = date('Y-m-d', strtotime('today'));
        $dateStart = date('Y-m-d', strtotime($dateEnd . ' - 1 month'));
        $activities = [];

        if ($request->has("arrayData")) {
            $arrayData = json_decode($request->get("arrayData"), true);
            if (is_array($arrayData)) {
                if (array_key_exists("search", $arrayData) && $arrayData["search"] != "") {
                    $search = $arrayData["search"];
                }
                if (array_key_exists("archive", $arrayData) && $arrayData["archive"] == "true") {
                    $archive = true;
                }
                if (array_key_exists("dateStart", $arrayData) && Helper::validateDate($arrayData["dateStart"])) {
                    $dateStart = $arrayData["dateStart"];
                    $default = false;
                }
                if (array_key_exists("dateEnd", $arrayData) && Helper::validateDate($arrayData["dateEnd"])) {
                    $dateEnd = $arrayData["dateEnd"];
                }
                if (array_key_exists("interval", $arrayData)) {
                    if ($arrayData["interval"] == "last30Days") {
                        $dateEnd = date('Y-m-d', strtotime('today'));
                        $dateStart = date('Y-m-d', strtotime($dateEnd . ' - 1 month'));
                        $default = true;
                    }
                    if ($arrayData["interval"] == "last3Months") {
                        $dateEnd = date('Y-m-d', strtotime('today'));
                        $dateStart = date('Y-m-d', strtotime($dateEnd . ' - 3 month'));
                    }
                }
            }
        }

        $days = $this->dateDifference($dateEnd, $dateStart);

        for ($x = 0; $x <= $days; $x++) {
            $currentDate = date('Y-m-d', strtotime($dateStart . ' + ' . $x . ' day'));
            $activities[$currentDate] = ["performance" => []];
        }

        $performances = Workoutsperformances::where("forTrainer", Auth::id())
            ->where("userId", $userId)
            ->whereNotNull("dateCompleted")
            ->where("dateCompleted", ">=", $dateStart . " 00:00:00")
            ->where("dateCompleted", "<=", $dateEnd . " 23:59:59")
            ->get();

        foreach ($performances as $performance) {
            $key = new DateTime($performance->dateCompleted);
            $key = $key->format('Y-m-d');
            array_push($activities[$key]["performance"], $performance);
        }

        return view("widgets.base.activityCalendar")
            ->with("activities", $activities)
            ->with("dateStart", $dateStart)
            ->with("dateEnd", $dateEnd)
            ->with("permissions", $permissions)
            ->with("default", $default)
            ->with("currentEndDate", $dateEnd)
            ->with("userId", $userId)
            ->with("user", $user);
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
        $validation = Validator::make($request->all(), Calendar::$rules);
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $calendar = new Calendar;
            $calendar->objective = $request->get("objective");
            $calendar->measureable = $request->get("measureable");
            $calendar->recordDate = $request->get("dateRecord");
            $calendar->userId = Auth::id();
            $calendar->save();
            Feeds::insertFeed("NewObjective", Auth::id(), Auth::user()->firstName, Auth::user()->lastName);
            return $this::responseJson(__("messages.ObjectiveAdded"));
        }
    }

    public function destroy($id)
    {
        $obj = Calendar::find($id);
        if (!$obj) {
            return $this::responseJsonError(__("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::id())) {
            $obj->delete();
            return $this::responseJson(__("messages.ObjectiveDeleted"));
        } else {
            return $this::responseJsonError(__("messages.Permissions"));
        }
    }
}
