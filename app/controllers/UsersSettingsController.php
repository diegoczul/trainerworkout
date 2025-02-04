<?php

class UsersSettingsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /settings
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.settings")
			->with("settings",Settings::where("userId","=",$userId)->take($this->pageSize)->get())
			->with("user",$user)
			->with("permissions",$permissions)
			->with("total",Settings::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$user = Users::find($userId);
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.settings")
			->with("settings",Settings::where("userId","=",$userId)->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Settings::where("userId","=",$userId)->count());
	}
	/**
	 * Show the form for creating a new resource.
	 * GET /settings/create
	 *
	 * @return Response
	 */

	public function AddEdit()
	{
		$user = Auth::user();


		$permissionFetched = Settings::where("name",$key)->where("userId",$user->id)->first();
		if($permissionFetched){
			$setting = Settings::find($permissionFetched->id);
			$variable = Input::get($key); 
			$setting->value = $variable;
			$perm->save();

		} else {
			$newPermission = new Settings();
			$newPermission->userId = $user->id;
			$newPermission->name = $name;
			$newPermission->value = Input::get($key); 
			$newPermission->save();
		}
		}

		return $this->responseJson(Lang::get("messages.PermissionsSaved"));
		
	}


	public function create()
	{

		$user = Auth::user();
		$userId = $user->id;

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]) $userId = Input::get("userId");
			$userId = Input::get("userId");
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$validation = Settings::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$settings = new Settings;
			$settings->name = Input::get("name");
			$settings->value = Input::get("value");
			$settings->userId = $user->id;
			$settings->save();
			return $this::responseJson(Lang::get("messages.SettingAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /settings
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /settings/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /settings/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /settings/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /settings/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

       
        
		$obj = Settings::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("SessionDeleted",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.SessionDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}