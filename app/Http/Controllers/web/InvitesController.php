<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use App\Models\Objectives;

class InvitesController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::user()->id;

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.base.objectives")
            ->with("user", $user)
            ->with("objectives", Objectives::where("userId", "=", $userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
            ->with("total", Objectives::where("userId", "=", $userId)->count());
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.full.objectives")
            ->with("user", $user)
            ->with("objectives", Objectives::where("userId", "=", $userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
            ->with("total", Objectives::where("userId", "=", $userId)->count());
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
            return $this::responseJson(Lang::get("messages.ObjectiveAdded"));
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
        $obj = Objectives::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
