<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Messages;
use App\Models\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\UserGroups;
use App\Models\Users;
use Yajra\DataTables\Facades\DataTables;

class UserGroupsController extends BaseController
{
    public $pageSize = 6;
    public $searchSize = 8;
    public $pageSizeFull = 10;

    //=======================================================================================================================
    // CONTROL PANEL
    //=======================================================================================================================

    public function _index()
    {
        return View::make('ControlPanel/UserGroups');
    }

    public function _ApiList(Request $request)
    {
        $response = UserGroups::when($request->has('groupId') && !empty($request->get("groupId")), function ($q) use($request){
                $q->where("groupId", $request->get("groupId"));
            })
            ->with("user")
            ->orderBy("updated_at", "DESC")
            ->latest();
        return DataTables::eloquent($response)
            ->addIndexColumn()
            ->make(true);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->get("hiddenId") && $request->get("hiddenId") != "") {
            return $this->_update($request->get("hiddenId"),$request);
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $validation = UserGroups::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            if (UserGroups::where("groupId", $request->get("hiddenGroupId"))->where("userId", $request->get("userId"))->count() == 0) {
                $group = new UserGroups;
                $group->userId = $request->get("userId");
                $group->groupId = $request->get("hiddenGroupId");
                $group->role = $request->get("role");

                if ($group->role == "Owner" || $group->role == "Admin") {
                    $user = Users::find($group->userId);
                    $user->admin = 1;
                    $user->save();
                }

                if ($group->groupId != 0) {
                    $group->save();
                }
            }

            return $this::responseJson(Messages::showControlPanel("GroupCreated"));
        }
    }

    public function _show($equipment)
    {
        return UserGroups::find($equipment);
    }

    public function _update($id,Request $request)
    {
        $validation = UserGroups::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $group = UserGroups::find($id);
            $group->userId = $request->get("userId");
            $group->groupId = $request->get("hiddenGroupId");
            $group->role = $request->get("role");

            if ($group->role == "Owner" || $group->role == "Admin") {
                $user = Users::find($group->userId);
                $user->admin = 1;
                $user->save();
            }

            if ($group->groupId != 0) {
                $group->save();
            }

            return $this::responseJson(Messages::showControlPanel("UserGroupModified"));
        }
    }

    public function _destroy($id)
    {
        $group = UserGroups::find($id);
        $group->delete();

        return $this::responseJson(Messages::showControlPanel("UserGroupDeleted"));
    }
}
