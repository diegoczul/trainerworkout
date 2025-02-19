<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\Memberships;
use App\Models\MembershipsUsers;
use App\Models\Users;

class MembershipsController extends BaseController
{
    public function indexMembershipManagement()
    {
//        if (!Auth::user()->membership) {
//            Auth::user()->updateToMembership(Config::get("constants.freeTrialMembershipId"));
//        }

        return View::make("MembershipManagement");
    }

    public function indexMembershipManagementOld()
    {
        return View::make("MembershipManagementOld");
    }

    public function create()
    {
        // No content provided
    }

    public function store()
    {
        // No content provided
    }

    public function show($id)
    {
        // No content provided
    }

    public function edit($id)
    {
        // No content provided
    }

    public function update($id)
    {
        // No content provided
    }

    public function destroy($id)
    {
        // No content provided
    }

    public function _indexUsers()
    {
        return View::make('ControlPanel/Memberships')
            ->with("users", Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName,' - ',email) as name"), "id")->orderBy("firstName", "ASC")->orderBy("lastName", "ASC")->pluck("name", "id"))
            ->with("memberships", Memberships::orderBy("name", "ASC")->pluck("name", "id"));
    }

    public function _ApiListUsers()
    {
        return $this::responseJson(["data" => MembershipsUsers::with("users")->with("membership")->orderBy("expiry", "ASC")->get()]);
    }

    public function _AddEditUsers(Request $request)
    {
        if ($request->filled("hiddenId")) {
            return $this->_updateUsers($request, $request->get("hiddenId"));
        } else {
            return $this->_createUsers($request);
        }
    }

    public function _createUsers(Request $request)
    {
        $validation = MembershipsUsers::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = new MembershipsUsers;
            $object->userId = $request->get("userId");
            $object->membershipId = $request->get("membershipId");
            $object->expiry = $request->get("expiry");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("FieldCreated"));
        }
    }

    public function _showUsers($object)
    {
        return MembershipsUsers::find($object);
    }

    public function _updateUsers(Request $request, $id)
    {
        $validation = MembershipsUsers::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = MembershipsUsers::find($id);
            $object->userId = $request->get("userId");
            $object->membershipId = $request->get("membershipId");
            $object->expiry = $request->get("expiry");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("FieldModified"));
        }
    }

    public function _destroyUsers($id)
    {
        $object = MembershipsUsers::find($id);
        $object->delete();

        return $this::responseJson(Messages::showControlPanel("FieldDeleted"));
    }

    public function _index()
    {
        return View::make('ControlPanel/MembershipsTypes');
    }

    public function _ApiList()
    {
        return $this::responseJson(["data" => Memberships::orderBy("name", "ASC")->get()]);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->filled("hiddenId")) {
            return $this->_update($request, $request->get("hiddenId"));
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $validation = Memberships::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = new Memberships;
            $object->name = $request->get("name");
            $object->description = $request->get("description");
            $object->features = $request->get("features");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("FieldCreated"));
        }
    }

    public function _show($object)
    {
        return Memberships::find($object);
    }

    public function _update(Request $request, $id)
    {
        $validation = Memberships::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = Memberships::find($id);
            $object->name = $request->get("name");
            $object->description = $request->get("description");
            $object->features = $request->get("features");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("FieldModified"));
        }
    }

    public function _destroy($id)
    {
        $object = Memberships::find($id);
        $object->delete();

        return $this::responseJson(Messages::showControlPanel("FieldDeleted"));
    }
}
