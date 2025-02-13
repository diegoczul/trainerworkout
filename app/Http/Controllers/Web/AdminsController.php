<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use App\Models\Weights;
use Illuminate\Support\Facades\Lang;

class AdminsController extends BaseController
{
    public $pageSize = 2;
    public $pageSizeFull = 9;

    public function index()
    {
        return view("ControlPanel.index");
    }

    public function AddEdit(Request $request)
    {
        if ($request->filled('id')) {
            return $this->update($request->get('id'));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $validation = Weights::validate($request->all());

        $user = auth()->user();
        $userId = $user->id;

        $permissions = null;
        if ($request->filled('userId')) {
            $permissions = Helper::checkPremissions(auth()->id(), $request->get('userId'));
            if ($permissions['view']) $userId = $request->get('userId');
            if ($permissions['add']) $userId = $request->get('userId');
        } else {
            $permissions = Helper::checkPremissions(auth()->id(), null);
        }

        if ($permissions['add']) {
            if ($validation->fails()) {
                return $this::responseJsonErrorValidation($validation->messages());
            } else {
                $weights = new Weights;
                if ($request->get('type') == 'pounds') {
                    $weights->weightPounds = $request->get('weight');
                    $weights->weightKilograms = number_format($request->get('weight') / 2.2, 2);
                } else {
                    $weights->weightPounds = number_format($request->get('weight') * 2.2, 2);
                    $weights->weightKilograms = $request->get('weight');
                }

                $weights->type = $request->get('type');
                $weights->recordDate = $request->get('dateRecord');
                $weights->userId = $userId;
                $weights->save();
                return $this::responseJson(Lang::get("messages.WeightAdded"));
            }
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function destroy($id)
    {
        $obj = Weights::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, auth()->id())) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.WeightDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
