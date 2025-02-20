<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Http\Libraries\Messages;
use App\Models\Equipments;
use App\Models\Users;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class EquipmentsController extends BaseController
{
    public $pageSize = 6;
    public $searchSize = 8;
    public $pageSizeFull = 10;

    public function _index()
    {
        $users = Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName) as name"), "id")
            ->orderBy("firstName", "ASC")
            ->orderBy("lastName", "ASC")
            ->pluck("name", "id");

        return View::make('ControlPanel.Equipments')->with("users", $users);
    }

    public function _ApiList()
    {
        return $this::responseJson(["data" => Equipments::orderBy("name", "ASC")->get()]);
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
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = Equipments::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $equipment = new Equipments;
        $equipment->name = $request->get("name");
        $equipment->nameEngine = $request->get("nameEngine");

        if ($request->filled("removeGreenScreen")) {
            $this->processGreenScreenImages($request, $equipment);
        } else {
            $this->processImages($request, $equipment);
        }

        $equipment->save();

        DB::statement("update equipments set name = ?, nameEngine = ? where id = ?", [
            $request->get("name"),
            $request->get("nameEngine"),
            $equipment->id
        ]);

        return $this::responseJson(Messages::showControlPanel("EquipmentCreated"));
    }

    public function _show($equipment)
    {
        return Equipments::find($equipment);
    }

    public function _update(Request $request, $id)
    {
        ini_set('max_execution_time', 3000);
        set_time_limit(3000);

        $validation = Equipments::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $equipment = Equipments::find($id);
        $equipment->name = $request->get("name");
        $equipment->nameEngine = $request->get("nameEngine");

        if ($request->filled("removeGreenScreen")) {
            $this->processGreenScreenImages($request, $equipment);
        } else {
            $this->processImages($request, $equipment);
        }

        $equipment->save();

        return $this::responseJson(Messages::showControlPanel("EquipmentModified"));
    }

    public function _destroy($id)
    {
        $equipment = Equipments::find($id);
        $equipment->delete();

        return $this::responseJson(Messages::showControlPanel("EquipmentDeleted"));
    }

    private function processGreenScreenImages(Request $request, $equipment)
    {
        if ($request->hasFile("image1")) {
            $images = Helper::saveImageGreenScreen(
                $request->file("image1"),
                Config::get("constants.moreExercises"),
                $request->get("light"),
                $request->get("modulation"),
                $request->get("feather"),
                $request->get("algo"),
                $request->get("replacer"),
                $request->get("color1"),
                $request->get("color2")
            );
            $equipment->image = $images["image"];
            $equipment->thumb = $images["thumb"];
        }

//        if ($request->hasFile("image2")) {
//            $images = Helper::saveImageGreenScreen(
//                $request->file("image2"),
//                Config::get("constants.moreExercises"),
//                $request->get("light"),
//                $request->get("modulation"),
//                $request->get("feather"),
//                $request->get("algo"),
//                $request->get("replacer"),
//                $request->get("color1"),
//                $request->get("color2")
//            );
//            $equipment->image2 = $images["image"];
//            $equipment->thumb2 = $images["thumb"];
//        }
    }

    private function processImages(Request $request, $equipment)
    {
        if ($request->hasFile("image1")) {
            $images = Helper::saveImage($request->file("image1"), Config::get("constants.moreExercises"));
            $equipment->image = $images["image"];
            $equipment->thumb = $images["thumb"];
        }

//        if ($request->hasFile("image2")) {
//            $images = Helper::saveImage($request->file("image2"), Config::get("constants.moreExercises"));
//            $equipment->image2 = $images["image"];
//            $equipment->thumb2 = $images["thumb"];
//        }
    }
}
