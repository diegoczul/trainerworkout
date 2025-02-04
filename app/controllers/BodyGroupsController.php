<?php

class BodyGroupsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /exercises
	 *
	 * @return Response
	 */

	public $pageSize = 15;
	public $searchSize = 15;
	public $pageSizeFull = 10;




	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/Bodygroups');
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Bodygroups::orderBy("name","ASC")->get()));
	}


	public function _AddEdit()
	{
		if(Input::has("hiddenId") and Input::get("hiddenId") != ""){
			return $this->_update(Input::get("hiddenId"));
		} else {
			return $this->_create();
		}		
	}

	public function _create()
	{


		$validation = Bodygroups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$bodygroup = new Bodygroups;
			$bodygroup->name = Input::get("name");
			$bodygroup->description = Input::get("description");
			$bodygroup->save();
			
	
			return $this::responseJson(Messages::showControlPanel("BodyGroupCreated"));	
		}
	}

	public function _show($bodygroup)
	{
		//
		return Bodygroups::find($bodygroup);
	}

	public function _update($id)
	{

		$validation = Bodygroups::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$bodygroup = Bodygroups::find($id);
			$bodygroup->name = Input::get("name");
			$bodygroup->description = Input::get("description");
			$bodygroup->save();

			return $this::responseJson(Messages::showControlPanel("BodygroupModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$bodygroup = Bodygroups::find($id);
		$bodygroup->delete();

		return $this::responseJson(Messages::showControlPanel("BodygroupDeleted"));
	}



	//=======================================================================================================================
	// API
	//======================================================================================================================



	public function APIsearchExercise(){

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->searchSize = Input::get("pageSize") + $this->searchSize;
		$search = Exercises::searchExercises(Input::get("search"), $this->searchSize);
		$data = array();
		$data["data"] = $search;
		$data["permissions"] = $permissions;
		$data["total"] = count($search);
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);


	}

	public function API_Exercise_Model() {
		$userId = Auth::user()->id;
		$exerciseId = -1;
		$permissions = null;
		if(Input::has("exerciseId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("exerciseId"));
			if($permissions["view"]){
				$userId 	= Input::get("userId");
				$exerciseId = Input::get("exerciseId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$exercise = Exercises::where("id",$exerciseId)->get();
		$exercise["templateSets"]	= TemplateSets::where("exerciseId", $exerciseId)->get();
		$exercise["sets"] 			= array();

		$data = array();
		$data["data"] = $exercise;
		$data["permissions"] = $permissions;
		$data["total"] = 1;
		$data["status"] = "ok";
		$data["message"] = "";

		return $this->responseJson($data);
	}


}