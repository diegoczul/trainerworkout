<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\Objectives;
use App\Models\Feeds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;

class ObjectivesController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get('userId'), 'w_objectives');
            if ($permissions["view"]) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has('pageSize')) {
            $this->pageSize = $request->get('pageSize') + $this->pageSize;
        }

        return View::make("widgets.base.objectives")
            ->with("objectives", Objectives::where("userId", "=", $userId)
                ->orderBy('recordDate', 'ASC')
                ->take($this->pageSize)
                ->get())
            ->with("permissions", $permissions)
            ->with("total", Objectives::where("userId", "=", $userId)->count());
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get('userId'), 'w_objectives');
            if ($permissions["view"]) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has('pageSize')) {
            $this->pageSize = $request->get('pageSize') + $this->pageSize;
        }

        return View::make("widgets.full.objectives")
            ->with("objectives", Objectives::where("userId", "=", $userId)
                ->orderBy('recordDate', 'ASC')
                ->take($this->pageSize)
                ->get())
            ->with("permissions", $permissions)
            ->with("total", Objectives::where("userId", "=", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        if ($request->has('id') && $request->get('id') != "") {
            return $this->update($request, $request->get('id'));
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
            Feeds::insertFeed("NewObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName, "objectiveAdded");
            return $this::responseJson(Lang::get("messages.ObjectiveAdded"));
        }
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

    public function update(Request $request, $id)
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
            // Feeds::insertFeed("DeleteObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            $obj->delete();
            return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function APIAddEdit(Request $request)
    {
        if ($request->has('id') && $request->get('id') != "") {
            return $this->APIupdate($request, $request->get('id'));
        } else {
            return $this->APIcreate($request);
        }
    }

    public function APIcreate(Request $request)
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
            $result = Helper::APIOK();
            $result["message"] = Lang::get("messages.ObjectiveAdded");
            return $result;
        }
    }
}
