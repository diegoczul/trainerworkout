<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use App\Models\Appointments;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $permissions = null;
        if ($request->filled("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->filled("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        $date = new \DateTime('today');
        return view("widgets.base.appointments")
            ->with("appointmentsOld", Appointments::where("userId", "=", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->take($this->pageSize)->get())
            ->with("appointmentsToday", Appointments::where("userId", "=", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->take($this->pageSize)->get())
            ->with("appointmentsNew", Appointments::where("userId", "=", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . "23:59:59")->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("appointmentsOldTotal", Appointments::where("userId", "=", $userId)->where("dateStart", "<", $date->format("Y-m-d ") . " 00:00:00")->count())
            ->with("appointmentsTodayTotal", Appointments::where("userId", "=", $userId)->whereBetween("dateStart", [$date->format("Y-m-d") . " 00:00:00", $date->format("Y-m-d") . " 23:59:59"])->count())
            ->with("appointmentsNewTotal", Appointments::where("userId", "=", $userId)->where("dateStart", ">", $date->format("Y-m-d ") . " 23:59:59")->count());
    }

    public function AddEdit(Request $request)
    {
        if ($request->filled("id")) {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $validation = Appointments::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonError(Lang::get("messages.CreateAppointmentError"));
        } else {
            $appointments = new Appointments;
            $appointments->appointment = $request->get("appointment");
            $appointments->dateStart = Helper::toDateTime($request->get("dateStart"));
            $appointments->dateEnd = Helper::toDateTime($request->get("dateEnd"));
            if ($request->get("appointmentTarget") != "") {
                $appointments->targetId = $request->get("appointmentTarget");
            }
            $appointments->name = $request->get("searchAppointmentTarget");
            $appointments->userId = $user->id;
            $appointments->save();
            return $this::responseJson(Lang::get("messages.AppointmentAdded"));
        }
    }

    public function store()
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

    public function destroy(Request $request)
    {
        if ($request->filled("id")) {
            $obj = Appointments::find($request->get("id"));
            if (!$obj) {
                return $this::responseJsonError(Lang::get("messages.NotFound"));
            }

            if ($this->checkPermissions($obj->userId, Auth::id())) {
                // Feeds::insertFeed("DeleteAppointment", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                $obj->delete();
                return $this::responseJson(Lang::get("messages.DeleteAppointment"));
            } else {
                return $this::responseJsonError(Lang::get("messages.Permissions"));
            }
        } else {
            return $this::responseJsonError(Lang::get("messages.AppointmentDeleteError"));
        }
    }
}
