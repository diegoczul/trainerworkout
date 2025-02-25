<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use App\Models\Measurements;
use App\Models\Feeds;
use App\Models\Clients;
use App\Models\Notifications;

class MeasurementsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"), "w_measurements");
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $datay1 = array();
        $datay2 = array();
        $y1 = array();
        $measurements = Measurements::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->get();

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.base.measurements")
            ->with("measurements", Measurements::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", Measurements::where("userId", "=", $userId)->count());
    }

    public function indexFull(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"), "w_measurements");
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
        }

        $datay1 = array();
        $datay2 = array();
        $y1 = array();
        $measurements = Measurements::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->get();

        if ($request->has("pageSize")) {
            $this->pageSizeFull = $request->get("pageSize") + $this->pageSizeFull;
        }

        return View::make("widgets.full.measurements")
            ->with("measurements", Measurements::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->take($this->pageSizeFull)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", Measurements::where("userId", "=", $userId)->count());
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
            if ($permissions["add"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($permissions["add"]) {
            $validation = Measurements::validate($request->all());
            if ($validation->fails()) {
                return $this::responseJsonErrorValidation($validation->messages());
            } else {
                $measurements = new Measurements;
                $measurements->chest = $request->get("chest");
                $measurements->recordDate = $request->get("recordDate");
                $measurements->abdominals = $request->get("abdominals");
                $measurements->bicepsLeft = $request->get("bicepsLeft");
                $measurements->bicepsRight = $request->get("bicepsRight");
                $measurements->legsLeft = $request->get("legsLeft");
                $measurements->legsRight = $request->get("legsRight");
                $measurements->forearmLeft = $request->get("forearmLeft");
                $measurements->forearmRight = $request->get("forearmRight");
                $measurements->calfLeft = $request->get("calfLeft");
                $measurements->calfRight = $request->get("calfRight");
                $measurements->waist = $request->get("waist");
                $measurements->userId = $userId;
                $measurements->save();
                Feeds::insertFeed("NewMeasurement", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName, "measurementsAdded");
                $trainers = Clients::returnAllTrainersOfClient($user->id);
                foreach ($trainers as $trainer) {
                    Notifications::insertDynamicNotification("NewMeasurementClient", $trainer->trainerId, $user->id, array("clientFirstName" => $user->firstName, "clientLastName" => $user->lastName, "clientLink" => $user->clientLink()), true, null, "message", "measurements", "feed");
                }
                return $this::responseJson(Lang::get("messages.MeasurementAdded"));
            }
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
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

    public function destroy($id)
    {
        $obj = Measurements::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.MeasurementDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
