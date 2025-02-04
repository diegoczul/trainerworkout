<?php

class EquipmentsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /equipments
	 *
	 * @return Response
	 */

	public $pageSize = 6;
	public $searchSize = 8;
	public $pageSizeFull = 10;



	//=======================================================================================================================
	// CONTROL PANEL
	//=======================================================================================================================
	

	public function _index()
	{
		return View::make('ControlPanel/Equipments')
			->with("users",Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName) as name "),"id")->orderBy("firstName","ASC")->orderBy("lastName","ASC")->lists("name","id"));
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>Equipments::orderBy("name","ASC")->get()));
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
		ini_set('max_execution_time', 3000);
        set_time_limit(3000);

		$validation = Equipments::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$equipment = new Equipments;
			$equipment->name = Input::get("name");
			$equipment->nameEngine = Input::get("nameEngine");

			if(Input::has("removeGreenScreen")){
				
				if(Input::hasFile("image1")) {
					
					$images = Helper::saveImageGreenScreen(Input::file("image1"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
					$equipment->image = $images["image"];
					$equipment->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImageGreenScreen(Input::file("image2"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
						$equipment->image2 = $images["image"];
						$equipment->thumb2 = $images["thumb"];
				}
			} else {
				
				if(Input::hasFile("image1")) {
					$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
					$equipment->image = $images["image"];
					$equipment->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$equipment->image2 = $images["image"];
						$equipment->thumb2 = $images["thumb"];
				}	
			}
		
			
			$equipment->save();

			DB::statement("update equipments set name = ?, nameEngine = ? where id = ?",[Input::get("name"), Input::get("nameEngine"), $equipment->id]);
			
			

			return $this::responseJson(Messages::showControlPanel("EquipmentCreated"));	
		}
	}

	public function _show($equipment)
	{
		//
		return Equipments::find($equipment);
	}

	public function _update($id)
	{
		ini_set('max_execution_time', 3000);
        set_time_limit(3000);
		$validation = Equipments::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$equipment = Equipments::find($id);
			$equipment->name = Input::get("name");
			$equipment->nameEngine = Input::get("nameEngine");
			
			if(Input::has("removeGreenScreen")){
					
				if(Input::hasFile("image1")) {
					$images = Helper::saveImageGreenScreen(Input::file("image1"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
					$equipment->image = $images["image"];
					$equipment->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImageGreenScreen(Input::file("image2"),Config::get("constants.moreExercises"),Input::get("light"),Input::get("modulation"),Input::get("feather"),Input::get("algo"),Input::get("replacer"),Input::get("color1"),Input::get("color2"));
						$equipment->image2 = $images["image"];
						$equipment->thumb2 = $images["thumb"];
				}
			} else {
				
				if(Input::hasFile("image1")) {
					$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
					$equipment->image = $images["image"];
					$equipment->thumb = $images["thumb"];
				}
				if(Input::hasFile("image2")) {
						$images = Helper::saveImage(Input::file("image2"),Config::get("constants.moreExercises"));
						$equipment->image2 = $images["image"];
						$equipment->thumb2 = $images["thumb"];
				}	
			}


			$equipment->save();

			

			

			return $this::responseJson(Messages::showControlPanel("EquipmentModified"));	
			
		}
	}

	public function _destroy($id)
	{
		//
		$equipment = Equipments::find($id);
		$equipment->delete();
		return $this::responseJson(Messages::showControlPanel("EquipmentDeleted"));
	}

}