<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Messages;
use App\Models\ExercisesTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

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
        $response = ExercisesTypes::orderBy("name", "ASC")->latest();
        return DataTables::eloquent($response)
            ->addIndexColumn()
            ->make(true);
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
        $validation = ExercisesTypes::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        } else {
            $object = new ExercisesTypes;
            $object->name = $request->get("name");
            $object->save();

            return $this->responseJson(Messages::showControlPanel("FieldCreated"));
        }
    }

    public function _show($object)
    {
        return ExercisesTypes::find($object);
    }

    public function _update(Request $request, $id)
    {
        $validation = ExercisesTypes::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        } else {
            $object = ExercisesTypes::find($id);
            $object->name = $request->get("name");
            $object->save();

            return $this->responseJson(Messages::showControlPanel("FieldModified"));
        }
    }

    public function _destroy($id)
    {
        $object = ExercisesTypes::find($id);
        $object->delete();

        return $this->responseJson(Messages::showControlPanel("FieldDeleted"));
    }
}
