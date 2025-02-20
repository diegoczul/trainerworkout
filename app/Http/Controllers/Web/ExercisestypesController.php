<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Messages;
use App\Models\Exercisestypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ExercisestypesController extends BaseController
{
    public $pageSize = 15;
    public $searchSize = 15;
    public $pageSizeFull = 10;

    //=======================================================================================================================
    // CONTROL PANEL
    //=======================================================================================================================

    public function _index()
    {
        return View::make('ControlPanel/ExercisesTypes');
    }

    public function _ApiList()
    {
        return $this->responseJson(["data" => Exercisestypes::orderBy("name", "ASC")->get()]);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->has("hiddenId") && $request->get("hiddenId") != "") {
            return $this->_update($request, $request->get("hiddenId"));
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $validation = Exercisestypes::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        } else {
            $object = new Exercisestypes;
            $object->name = $request->get("name");
            $object->save();

            return $this->responseJson(Messages::showControlPanel("FieldCreated"));
        }
    }

    public function _show($object)
    {
        return Exercisestypes::find($object);
    }

    public function _update(Request $request, $id)
    {
        $validation = Exercisestypes::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        } else {
            $object = Exercisestypes::find($id);
            $object->name = $request->get("name");
            $object->save();

            return $this->responseJson(Messages::showControlPanel("FieldModified"));
        }
    }

    public function _destroy($id)
    {
        $object = Exercisestypes::find($id);
        $object->delete();

        return $this->responseJson(Messages::showControlPanel("FieldDeleted"));
    }
}
