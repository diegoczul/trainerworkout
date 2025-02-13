<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\Users;
use App\Models\Ratings;
use Illuminate\Http\Request;
use Messages;

class RatingsController extends BaseController
{
    public $pageSize = 15;
    public $searchSize = 15;
    public $pageSizeFull = 10;

    public function _index()
    {
        $trainers = Users::select(DB::raw("concat(firstName,' ',lastName,' - ',email) as fullname"), "id")
            ->where("userType", "Trainer")
            ->pluck("fullname", "id");

        return View::make('ControlPanel/Ratings')
            ->with("trainers", $trainers);
    }

    public function _ApiList()
    {
        return $this::responseJson([
            "data" => Ratings::with("trainer")->orderBy("name", "ASC")->get()
        ]);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->filled("hiddenId")) {
            return $this->_update($request->get("hiddenId"));
        } else {
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $validation = Ratings::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = new Ratings;
            $object->name = $request->get("name");
            $object->value = $request->get("value");
            $object->ownerId = $request->get("trainer");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("Created"));
        }
    }

    public function _show($object)
    {
        return Ratings::find($object);
    }

    public function _update($id, Request $request)
    {
        $validation = Ratings::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $object = Ratings::find($id);
            $object->value = $request->get("value");
            $object->ownerId = $request->get("trainer");
            $object->name = $request->get("name");
            $object->save();

            return $this::responseJson(Messages::showControlPanel("Modified"));
        }
    }

    public function _destroy($id)
    {
        $object = Ratings::find($id);
        $object->delete();

        return $this::responseJson(Messages::showControlPanel("Deleted"));
    }
}
