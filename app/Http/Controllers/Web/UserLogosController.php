<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\UserLogos;
use App\Models\Users;
use Intervention\Image\Facades\Image;
use App\Http\Libraries\Messages;
use Yajra\DataTables\Facades\DataTables;

class UserLogosController extends BaseController
{
    public $pageSize = 6;
    public $searchSize = 8;
    public $pageSizeFull = 10;

    //=======================================================================================================================
    // CONTROL PANEL
    //=======================================================================================================================

    public function _index()
    {
        return View::make('ControlPanel/UserLogos')
            ->with("users", Users::select(DB::raw("concat('id: ', id, ' - ', firstName, ' ', lastName, ' ', email) as name"), "id")
                ->orderBy("firstName", "ASC")
                ->orderBy("lastName", "ASC")
                ->pluck("name", "id"));
    }

    public function _ApiList()
    {
        $response = UserLogos::with("user")->orderBy("id", "ASC")->latest();
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
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = UserLogos::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $userlogo = new UserLogos;
            $userlogo->userId = $request->get("userId");
            $user = Users::find($request->get("userId"));

            if ($request->hasFile("image1")) {
                $images = Helper::saveImage($request->file("image1"), $user->getPath() . Config::get("constants.profilePath") . "/" . $user->id);
                $userlogo->image = $images["image"];
                $userlogo->thumb = $images["thumb"];
            }

            if ($request->get("active")) {
                UserLogos::where("userId", $request->get("userId"))->update(["active" => 0]);
                $userlogo->active = 1;
            }

            $userlogo->save();
            return $this::responseJson(Messages::showControlPanel("UserLogoCreated"));
        }
    }

    public function _show($equipment)
    {
        return UserLogos::find($equipment);
    }

    public function _update($id,Request $request)
    {
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = UserLogos::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $userlogo = UserLogos::find($id);
            $userlogo->userId = $request->get("userId");

            if ($request->hasFile("image1")) {
                $images = Helper::saveImage($request->file("image1"), Config::get("constants.moreExercises"));
                $userlogo->image = $images["image"];
                $userlogo->thumb = $images["thumb"];
            }

            if ($request->get("active")) {
                UserLogos::where("userId", $request->get("userId"))->update(["active" => 0]);
                $userlogo->active = 1;
            }

            $userlogo->save();
            return $this::responseJson(Messages::showControlPanel("UserLogoModified"));
        }
    }

    public function rotateRight(Request $request)
    {
        $id = $request->get("id");
        $obj = UserLogos::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(-90)->save();
            $image = Image::make($obj->thumb);
            $image->rotate(-90)->save();
            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateLeft(Request $request)
    {
        $id = $request->get("id");
        $obj = UserLogos::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(90)->save();
            $image = Image::make($obj->thumb);
            $image->rotate(90)->save();
            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateRight1(Request $request)
    {
        $id = $request->get("id");
        $obj = UserLogos::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(-90)->save();
            $image = Image::make($obj->thumb);
            $image->rotate(-90)->save();
            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function rotateLeft1(Request $request)
    {
        $id = $request->get("id");
        $obj = UserLogos::find($id);

        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(90)->save();
            $image = Image::make($obj->thumb);
            $image->rotate(90)->save();
            return $this::responseJson(Lang::get("messages.ImageRotated"));
        }
    }

    public function _destroy($id)
    {
        $equipment = UserLogos::find($id);
        $equipment->delete();
        return $this::responseJson(Messages::showControlPanel("UserLogoDeleted"));
    }
}
