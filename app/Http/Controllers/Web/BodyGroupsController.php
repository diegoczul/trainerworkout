<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\Bodygroups;
use App\Models\Exercises;
use App\Models\TemplateSets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Messages;

class BodyGroupsController extends BaseController
{
    public $pageSize = 15;
    public $searchSize = 15;
    public $pageSizeFull = 10;

    //=======================================================================================================================
    // CONTROL PANEL
    //=======================================================================================================================

    public function _index()
    {
        return view('ControlPanel.Bodygroups');
    }

    public function _ApiList()
    {
        return $this->responseJson(['data' => Bodygroups::orderBy('name', 'ASC')->get()]);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->filled('hiddenId')) {
            return $this->_update($request->get('hiddenId'));
        }
        return $this->_create($request);
    }

    public function _create(Request $request)
    {
        $validation = Bodygroups::validate($request->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        }

        $bodygroup = new Bodygroups;
        $bodygroup->name = $request->get('name');
        $bodygroup->description = $request->get('description');
        $bodygroup->save();

        return $this->responseJson(Messages::showControlPanel('BodyGroupCreated'));
    }

    public function _show(int $bodygroup)
    {
        return Bodygroups::find($bodygroup);
    }

    public function _update(int $id)
    {
        $validation = Bodygroups::validate(request()->all());
        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        }

        $bodygroup = Bodygroups::find($id);
        $bodygroup->name = request('name');
        $bodygroup->description = request('description');
        $bodygroup->save();

        return $this->responseJson(Messages::showControlPanel('BodygroupModified'));
    }

    public function _destroy(int $id): JsonResponse
    {
        $bodygroup = Bodygroups::find($id);
        $bodygroup->delete();

        return $this->responseJson(Messages::showControlPanel('BodygroupDeleted'));
    }

    //=======================================================================================================================
    // API
    //=======================================================================================================================

    public function APIsearchExercise(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->filled('userId')) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('userId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->filled('pageSize')) {
            $this->searchSize += $request->get('pageSize');
        }

        $search = Exercises::searchExercises($request->get('search'), $this->searchSize);

        $data = [
            'data' => $search,
            'permissions' => $permissions,
            'total' => count($search),
            'status' => 'ok',
            'message' => '',
        ];

        return $this->responseJson($data);
    }

    public function API_Exercise_Model(Request $request)
    {
        $userId = Auth::id();
        $exerciseId = -1;
        $permissions = null;

        if ($request->filled('exerciseId')) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get('exerciseId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
                $exerciseId = $request->get('exerciseId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        $exercise = Exercises::where('id', $exerciseId)->get();
        $exercise['templateSets'] = TemplateSets::where('exerciseId', $exerciseId)->get();
        $exercise['sets'] = [];

        $data = [
            'data' => $exercise,
            'permissions' => $permissions,
            'total' => 1,
            'status' => 'ok',
            'message' => '',
        ];

        return $this->responseJson($data);
    }
}
