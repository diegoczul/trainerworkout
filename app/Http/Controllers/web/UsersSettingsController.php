<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class UsersSettingsController extends Controller
{
    public $pageSize = 9;
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

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return view("widgets.base.settings")
            ->with("settings", Settings::where("userId", $userId)->take($this->pageSize)->get())
            ->with("user", $user)
            ->with("permissions", $permissions)
            ->with("total", Settings::where("userId", $userId)->count());
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
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return view("widgets.full.settings")
            ->with("settings", Settings::where("userId", $userId)->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", Settings::where("userId", $userId)->count());
    }

    public function AddEdit(Request $request)
    {
        $user = Auth::user();
        $key = $request->get("name");

        $permissionFetched = Settings::where("name", $key)->where("userId", $user->id)->first();
        if ($permissionFetched) {
            $setting = Settings::find($permissionFetched->id);
            $variable = $request->get("value");
            $setting->value = $variable;
            $setting->save();
        } else {
            $newPermission = new Settings();
            $newPermission->userId = $user->id;
            $newPermission->name = $key;
            $newPermission->value = $request->get("value");
            $newPermission->save();
        }

        return $this->responseJson(Lang::get("messages.PermissionsSaved"));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $validation = Settings::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        } else {
            $settings = new Settings();
            $settings->name = $request->get("name");
            $settings->value = $request->get("value");
            $settings->userId = $user->id;
            $settings->save();
            return $this->responseJson(Lang::get("messages.SettingAdded"));
        }
    }

    public function destroy($id)
    {
        $obj = Settings::find($id);
        if (!$obj) {
            return $this->responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this->responseJson(Lang::get("messages.SessionDeleted"));
        } else {
            return $this->responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
