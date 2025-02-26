<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Models\Feeds;
use App\Models\Pictures;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class PicturesController extends BaseController
{
    public $pageSize = 2;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get('userId'), 'w_pictures');
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has('pageSize')) {
            $this->pageSize = $request->get('pageSize') + $this->pageSize;
        }

        return view('widgets.full.pictures', [
            'pictures' => Pictures::where('userId', '=', $userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get(),
            'permissions' => $permissions,
            'total' => Pictures::where('userId', '=', $userId)->count()
        ]);
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get('userId'), 'w_pictures');
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has('pageSize')) {
            $this->pageSize = $request->get('pageSize') + $this->pageSize;
        }

        return view('widgets.full.pictures', [
            'pictures' => Pictures::where('userId', '=', $userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get(),
            'permissions' => $permissions,
            'total' => Pictures::where('userId', '=', $userId)->count()
        ]);
    }

    public function AddEdit(Request $request)
    {
        if ($request->has('id') && $request->get('id') != '') {
            return $this->update($request->get('id'));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $validation = Pictures::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $pictures = new Pictures();
        $pictures->recordDate = $request->get('recordDate');
        $pictures->userId = $user->id;
        Feeds::insertFeed('NewPictures', $user->id, $user->firstName, $user->lastName, 'picturesAdded');
        $pictures->save();

        Helper::checkUserFolder($user->id);

        $imageFields = ['front', 'back', 'left', 'right'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $images = Helper::saveImage($request->file($field), $user->getPath() . Config::get('constants.picturesPath') . '/' . $pictures->id);
                $pictures->$field = $images['image'];
                $pictures->{'thumb' . ucfirst($field)} = $images['thumb'];
            }
        }

        $pictures->save();

        return $this::responseJson(Lang::get('messages.PicturesAdded'));
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        $obj = Pictures::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get('messages.NotFound'));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get('messages.PicturesDeleted'));
        }

        return $this::responseJsonError(Lang::get('messages.Permissions'));
    }

    public function APIindex(Request $request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get('userId'));
            if ($permissions['view']) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->has('pageSize')) {
            $this->pageSize = $request->get('pageSize') + $this->pageSize;
        }

        $this->pageSize = 999;
        return [
            'status' => 'ok',
            'data' => Pictures::select("id","userId","title","recordDate","front as front_url","back as back_url","left as left_url","right as right_url","deleted_at","created_at","updated_at","thumbFront as thumb_front_url","thumbBack as thumb_back_url","thumbLeft as thumb_left_url","thumbRight as thumb_right_url","reminded",)->where('userId', '=', $userId)->orderBy('recordDate', 'DESC')->take($this->pageSize)->get(),
            'permissions' => $permissions,
            'total' => Pictures::where('userId', '=', $userId)->count(),
        ];
    }

    public function APIAddEdit(Request $request)
    {
        if ($request->has('id') && $request->get('id') != '') {
            return $this->APIupdate($request->get('id'));
        }

        return $this->APIcreate($request);
    }

    public function APIcreate(Request $request)
    {
        $user = Auth::user();

        $validation = Pictures::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $pictures = new Pictures();
        $pictures->recordDate = $request->get('recordDate');
        $pictures->userId = $user->id;
        Feeds::insertFeed('NewPictures', $user->id, $user->firstName, $user->lastName);
        $pictures->save();

        Helper::checkUserFolder($user->id);

        $imageFields = ['front', 'back', 'left', 'right'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $images = Helper::saveImage($request->file($field), $user->getPath() . Config::get('constants.picturesPath') . '/' . $pictures->id);
                $pictures->$field = $images['image'];
                $pictures->{'thumb' . ucfirst($field)} = $images['thumb'];
            }
        }

        $pictures->save();

        return [
            'status' => 'ok',
            'message' => Lang::get('messages.PicturesAdded')
        ];
    }
}
