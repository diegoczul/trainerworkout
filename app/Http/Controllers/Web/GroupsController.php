<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use App\Models\Groups;
use App\Models\Users;
use App\Models\UserGroups;

class GroupsController extends BaseController
{
    public $pageSize = 6;
    public $searchSize = 8;
    public $pageSizeFull = 10;

    public function showGroup()
    {
        $user = Auth::user();
        $groupUser = $user->group;
        if ($groupUser) {
            $group = Groups::find($groupUser->groupId);

            if ($group) {
                $userGroups = UserGroups::with('user')->where('groupId', $group->id)->get();
                return View::make('trainer.employeeManagement')
                    ->with('userGroups', $userGroups)
                    ->with('groupUser', $groupUser)
                    ->with('user', $user)
                    ->with('group', $group);
            }
        }
    }

    public function resendGroupInvitation($userId)
    {
        $user = Users::find($userId);
        $author = Auth::user();
        $authorFirstName = $author->firstName;
        $authorLastName = $author->lastName;
        $authorEmail = $author->email;

        if ($user) {
            $user->sendInviteGroup('', $authorFirstName, $authorLastName, $authorEmail);
            return $this::responseJson(Lang::get('messages.InvitationSent'));
        } else {
            return $this::responseJsonError(Lang::get('messages.Oops'));
        }
    }

    public function changeRole(Request $request)
    {
        $userId = $request->get('user');
        $role = $request->get('user');
        $user = Auth::user();
        $groupUser = $user->group;
        $group = Groups::find($groupUser->groupId);
        $userGroup = UserGroups::with('user')->where('groupId', $group->id)->where('userId', $userId)->first();
        if ($userGroup) {
            $userGroup->role = $request->get('role');
            $userGroup->save();
        }
        return $this::responseJson(Lang::get('messages.EmployeeUpdated'));
    }

    public function removeAccess($userId)
    {
        $user = Auth::user();
        $groupUser = $user->group;
        $group = Groups::find($groupUser->groupId);
        $userGroups = UserGroups::with('user')->where('groupId', $group->id)->where('userId', $userId)->delete();
        return $this::responseJson(Lang::get('messages.EmployeesRemoved'));
    }

    public function addEmployees(Request $request)
    {
        $counter = 0;
        $elements = count($request->get('firstName'));

        $owner = Auth::user();
        $user = null;
        $group = $owner->group;

        for ($x = 0; $x < $elements; $x++) {
            if (Users::where('email', $request->get("email")[$counter])->count() == 0) {
                $newuser = new Users;
                $newuser->firstName = ucfirst($request->get("firstName")[$counter]);
                $newuser->lastName = ucfirst($request->get("lastName")[$counter]);
                $newuser->email = ucfirst($request->get("email")[$counter]);
                $newuser->userType = 'Trainer';
                $newuser->save();
                $newuser->freebesTrainer();

                $newuser->sendInviteGroup($owner->firstName, $owner->lastName, $owner->email);
                $user = $newuser;
            } else {
                $user = Users::where('email', $request->get("email")[$counter])->first();
                $user->sendInviteGroup($owner->firstName, $owner->lastName, $owner->email);
            }

            if (UserGroups::where('groupId', $group->groupId)->where('userId', $user->id)->count() == 0) {
                $userGroup = new UserGroups;
                $userGroup->userId = $user->id;
                $userGroup->role = $request->get("role")[$counter];
                $userGroup->groupId = $group->groupId;
                if ($userGroup->groupId != 0) $userGroup->save();
            }
            $counter++;
        }

        return Redirect::route('employeeManagement')->with('message', Lang::get('messages.EmployeesInvited'));
    }

    public function _index()
    {
        return View::make('ControlPanel/Groups')
            ->with('users', Users::select(DB::raw("concat(firstname,' ',lastName,' ',email) as fullName"), 'id')->orderBy('firstName')->orderBy('lastName')->pluck('fullName', 'id'));
    }

    public function _ApiList()
    {
        return $this::responseJson(['data' => Groups::orderBy('name', 'ASC')->get()]);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->has('hiddenId') && $request->get('hiddenId') != "") {
            return $this->_update($request->get('hiddenId'), $request);
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $validation = Groups::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $group = new Groups;
            $group->name = $request->get('name');
            $group->save();

            return $this::responseJson(Messages::showControlPanel('GroupCreated'));
        }
    }

    public function _show($equipment)
    {
        return Groups::find($equipment);
    }

    public function _update($id, Request $request)
    {
        $validation = Groups::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $group = Groups::find($id);
            $group->name = $request->get('name');
            $group->save();

            return $this::responseJson(Messages::showControlPanel('UserLogoModified'));
        }
    }

    public function _destroy($id)
    {
        $group = Groups::find($id);
        if ($group->countUsers() == 0) {
            $group->delete();
            return $this::responseJson(Messages::showControlPanel('GroupDeleted'));
        } else {
            return $this::responseJsonError(Messages::showControlPanel('GroupEmpty'));
        }
    }
}
