<?php

class ExercisestypesController extends \BaseController {

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
		return View::make('ControlPanel/ExercisesTypes');
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Exercisestypes::orderBy("name","ASC")->get()));
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


		$validation = Exercisestypes::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = new Exercisestypes;
			$object->name = Input::get("name");
			$object->save();
			
	
			return $this::responseJson(Messages::showControlPanel("FieldCreated"));	
		}
	}

	public function _show($object)
	{
		//
		return Exercisestypes::find($object);
	}

	public function _update($id)
	{

		$validation = Exercisestypes::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$object = Exercisestypes::find($id);
			$object->name = Input::get("name");
			$object->save();

			return $this::responseJson(Messages::showControlPanel("FieldModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$object = Exercisestypes::find($id);
		$object->delete();

		return $this::responseJson(Messages::showControlPanel("FieldDeleted"));
	}



	


}