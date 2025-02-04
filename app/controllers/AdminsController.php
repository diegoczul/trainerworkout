<?php

class AdminsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /weights
	 *
	 * @return Response
	 */

	public $pageSize = 2;
	public $pageSizeFull = 9;

	public function index()
	{
			          
		return View::make("ControlPanel.index");
	}




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
		
		$validation = Weights::validate(Input::all());
		
		$user = Auth::user();
		$userId = $user->id;

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]) $userId = Input::get("userId");
			if($permissions["add"])	$userId = Input::get("userId");
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if($permissions["add"]){
			if($validation->fails()){
				return $this::responseJsonErrorValidation($validation->messages());
			} else {
				$weights = new Weights;
				if(Input::get("type") == "pounds"){
					$weights->weightPounds = Input::get("weight");
					$weights->weightKilograms = number_format(Input::get("weight")/2.2,2);
				} else {
					$weights->weightPounds = number_format(Input::get("weight")*2.2,2);
					$weights->weightKilograms = Input::get("weight");
				}
				
				$weights->type = Input::get("type");
				$weights->recordDate = Input::get("dateRecord");
				$weights->userId = $userId;
				$weights->save();
				return $this::responseJson(Lang::get("messages.WeightAdded"));	
			}
		}else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /weights
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /weights/{id}
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
	 * GET /weights/{id}/edit
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
	 * PUT /weights/{id}
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
	 * DELETE /weights/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Weights::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
			$obj->delete();
			return $this::responseJson(Lang::get("messages.WeightDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	}

}