<?php

class RatingsController extends \BaseController {
	
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
		$trainers = Users::select(DB::raw("concat(firstName,' ',lastName,' - ',email) as fullname"),"id")->where("userType","Trainer")->lists("fullname","id");
		return View::make('ControlPanel/Ratings')
			->with("trainers",$trainers);
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Ratings::with("trainer")->orderBy("name","ASC")->get()));
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


		$validation = Ratings::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = new Ratings;
			$object->name = Input::get("name");
			$object->value = Input::get("value");
			$object->ownerId = Input::get("trainer");
			$object->save();
			
	
			return $this::responseJson(Messages::showControlPanel("Created"));	
		}
	}

	public function _show($object)
	{
		//
		return Ratings::find($object);
	}

	public function _update($id)
	{

		$validation = Ratings::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = Ratings::find($id);
			$object->value = Input::get("value");
			$object->ownerId = Input::get("trainer");
			$object->name = Input::get("name");
			$object->save();

			return $this::responseJson(Messages::showControlPanel("Modified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$object = Ratings::find($id);
		$object->delete();

		return $this::responseJson(Messages::showControlPanel("Deleted"));
	}



	

}