<?php

class SessionsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /sessions
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

		return View::make("widgets.base.sessions")
			->with("sessions",TrainerSessions::where("userId","=",$userId)->take($this->pageSize)->get())
			->with("user",$user)
			->with("permissions",$permissions)
			->with("total",TrainerSessions::where("userId","=",$userId)->count());
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

		return View::make("widgets.full.sessions")
			->with("sessions",TrainerSessions::where("userId","=",$userId)->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",TrainerSessions::where("userId","=",$userId)->count());
	}
	/**
	 * Show the form for creating a new resource.
	 * GET /sessions/create
	 *
	 * @return Response
	 */

	public function AddEdit()
	{
		if(Input::has("id") and Input::get("id") != ""){
			return $this->update(Input::get("id"));
		} else {
			return $this->create();
		}		
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

		$validation = TrainerSessions::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$sessions = new TrainerSessions;
			$sessions->name = Input::get("name");
			$sessions->description = Input::get("description");
			$sessions->price = Input::get("price");
			$sessions->numberOfSessions = Input::get("numberOfSessions");
			$sessions->timePerSession = Input::get("timePerSession");
			$sessions->userId = $user->id;
			$sessions->save();
			Feeds::insertFeed("NewSession",$user->id,$user->firstName,$user->lastName);
			return $this::responseJson(Lang::get("messages.SessionAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /sessions
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /sessions/{id}
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
	 * GET /sessions/{id}/edit
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
	 * PUT /sessions/{id}
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
	 * DELETE /sessions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$obj = TrainerSessions::find($id);
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