<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use App\Models\Weights;
use App\Models\Users;
use App\Models\Feeds;

class WeightsController extends BaseController
{
    public $pageSize = 2;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $datay1 = [];
        $datay2 = [];
        $y1 = [];
        $weights = Weights::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->get();
        $offset = floor($weights->count() / 8);
        $x = 0;

        if ($weights->count() > 1) {
            foreach ($weights as $weight) {
                if ($x >= $offset) {
                    array_push($datay1, $weight->weightPounds);
                    array_push($datay2, $weight->weightKilograms);
                    array_push($y1, Helper::date($weight->recordDate));
                    $x = 0;
                }
                $x++;
            }
        }

        if ($request->get("pageSize")) $this->pageSize = $request->get("pageSize") + $this->pageSize;

        return View::make("widgets.base.weight")
            ->with("weights", Weights::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get())
            ->with("total", Weights::where("userId", "=", $userId)->count())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("datay1", array_reverse($datay1))
            ->with("datay2", array_reverse($datay2))
            ->with("y1", array_reverse($y1));
    }

    public function indexFull(Request $request)
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

        $datay1 = [];
        $datay2 = [];
        $y1 = [];
        $weights = Weights::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->get();
        $offset = floor($weights->count() / 16);
        $x = 0;

        if ($weights->count() > 1) {
            foreach ($weights as $weight) {
                if ($x >= $offset) {
                    array_push($datay2, $weight->weightKilograms);
                    array_push($datay1, $weight->weightPounds);
                    array_push($y1, Helper::date($weight->recordDate));
                    $x = 0;
                }
                $x++;
            }
        }

        if ($request->get("pageSize")) $this->pageSizeFull = $request->get("pageSize") + $this->pageSizeFull;

        return View::make("widgets.full.weight")
            ->with("weights", Weights::where("userId", "=", $userId)->orderBy('recordDate', 'DESC')->take($this->pageSizeFull)->get())
            ->with("total", Weights::where("userId", "=", $userId)->count())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("datay1", array_reverse($datay1))
            ->with("datay2", array_reverse($datay2))
            ->with("y1", array_reverse($y1));
    }

    public function AddEdit(Request $request)
    {
        if ($request->get("id") && $request->get("id") != "") {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $validation = Weights::validate($request->all());

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
            if ($validation->fails()) {
                return $this::responseJsonErrorValidation($validation->messages());
            } else {
                $weights = new Weights;
                if ($request->get("type") == "pounds") {
                    $weights->weightPounds = $request->get("weight");
                    $weights->weightKilograms = number_format($request->get("weight") / 2.2, 2);
                } else {
                    $weights->weightPounds = number_format($request->get("weight") * 2.2, 2);
                    $weights->weightKilograms = $request->get("weight");
                }

                $weights->type = $request->get("type");
                $weights->recordDate = $request->get("dateRecord");
                $weights->userId = $userId;
                $weights->save();
                Feeds::insertFeed("NewWeight", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName, "weightAdded");
                return $this::responseJson(Lang::get("messages.WeightAdded"));
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
        $obj = Weights::find($id);
        if (!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.WeightDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function APIAddEdit(Request $request)
    {
        if ($request->get("id") && $request->get("id") != "") {
            return $this->APIUpdate($request);
        } else {
            return $this->APIcreate($request);
        }
    }

    public function APIUpdate(Request $request)
    {
        $validation = Weights::validate($request->all());

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

        $type = "";

        if ($permissions["add"]) {
            if ($validation->fails()) {
                $return = Helper::APIERROR();
                $return["messages"] = $validation->messages();
                return $return;
            } else {
                $weights = Weights::find($request->get("id"));
                if ($weights) {
                    if (!$request->has($type)) $type = "pounds";
                    if ($type == "pounds") {
                        $weights->weightPounds = $request->get("weight");
                        $weights->weightKilograms = number_format($request->get("weight") / 2.2, 2);
                    } else {
                        $weights->weightPounds = number_format($request->get("weight") * 2.2, 2);
                        $weights->weightKilograms = $request->get("weight");
                    }

                    $weights->type = $type;
                    $weights->recordDate = $request->get("recordDate");
                    $weights->userId = $userId;
                    $weights->save();
                    Feeds::insertFeed("NewWeight", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                    $return = Helper::APIOK();
                    $return["message"] = Lang::get("messages.WeightUpdated");
                    $return['data'] = $weights;
                    return $return;
                }
            }
        } else {
            $return["messages"] = Lang::get("messages.Permissions");
            return $return;
        }
    }

    public function APIcreate(Request $request)
    {
        $validation = Weights::validate($request->all());

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

        $type = "";

        if ($permissions["add"]) {
            if ($validation->fails()) {
                $return = Helper::APIERROR();
                $return["messages"] = $validation->messages();
                return $return;
            } else {
                $weights = new Weights;
                if (!$request->has($type)) $type = "pounds";
                if ($type == "pounds") {
                    $weights->weightPounds = $request->get("weight");
                    $weights->weightKilograms = number_format($request->get("weight") / 2.2, 2);
                } else {
                    $weights->weightPounds = number_format($request->get("weight") * 2.2, 2);
                    $weights->weightKilograms = $request->get("weight");
                }

                $weights->type = $type;
                $weights->recordDate = $request->get("dateRecord");
                $weights->userId = $userId;
                $weights->save();
                Feeds::insertFeed("NewWeight", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
                $return = Helper::APIOK();
                $return["message"] = Lang::get("messages.WeightAdded");
                $return['data'] = $weights;
                return $return;
            }
        } else {
            $return["messages"] = Lang::get("messages.Permissions");
            return $return;
        }
    }
}
