<?php

class ObjectivesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /objectives
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{

		$userId = Auth::user()->id;

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_objectives");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.objectives")
			->with("objectives",Objectives::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Objectives::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"),"w_objectives");
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.objectives")
			->with("objectives",Objectives::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Objectives::where("userId","=",$userId)->count());
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /objectives/create
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
		
		$validation = Objectives::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$objectives = new Objectives;
			$objectives->objective = Input::get("objective");
			$objectives->measureable = Input::get("measureable");
			$objectives->recordDate = Input::get("dateRecord");
			$objectives->userId = Auth::user()->id;
			$objectives->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName,"objectiveAdded");
			return $this::responseJson(Lang::get("messages.ObjectiveAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /objectives
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /objectives/{id}
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
	 * GET /objectives/{id}/edit
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
	 * PUT /objectives/{id}
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
	 * DELETE /objectives/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$obj = Objectives::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeleteObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

	//=======================================================================================================================
	// API
	//=======================================================================================================================

	public function APIAddEdit()
	{
		if(Input::has("id") and Input::get("id") != ""){
			return $this->APIupdate(Input::get("id"));
		} else {
			return $this->APIcreate();
		}		
	}


	public function APIcreate()
	{
		
		$validation = Objectives::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$objectives = new Objectives;
			$objectives->objective = Input::get("objective");
			$objectives->measureable = Input::get("measureable");
			$objectives->recordDate = Input::get("dateRecord");
			$objectives->userId = Auth::user()->id;
			$objectives->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$result = Helper::APIOK();
			$result["message"] = Lang::get("messages.ObjectiveAdded");
			return $result;	
		}
	}

	

}