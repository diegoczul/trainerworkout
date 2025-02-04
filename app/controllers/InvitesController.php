<?php

class InvitesController extends \BaseController {

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
		$user = Auth::user();
		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.objectives")
			->with("user",$user)
			->with("objectives",Objectives::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("total",Objectives::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.objectives")
			->with("user",$user)
			->with("objectives",Objectives::where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
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
			$obj->delete();
			return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}