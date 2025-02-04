<?php

class UserLogosController extends \BaseController {

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
		return View::make('ControlPanel/UserLogos')
			->with("users",Users::select(DB::raw("concat('id: ',id,' - ',firstName,' ',lastName,' ',email) as name "),"id")->orderBy("firstName","ASC")->orderBy("lastName","ASC")->lists("name","id"));
	}

	public function _ApiList()
	{
		return $this::responseJson(array("data"=>UserLogos::with("user")->orderBy("id","ASC")->get()));
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

		$validation = UserLogos::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$userlogo = new UserLogos;
			$userlogo->userId = Input::get("userId");

			$user = Users::find(Input::get("userId"));

				
			if(Input::hasFile("image1")) {
				$images = Helper::saveImage(Input::file("image1"),$user->getPath().Config::get("constants.profilePath")."/".$user->id);
				$userlogo->image = $images["image"];
				$userlogo->thumb = $images["thumb"];
			}

			if(Input::has("active")){
				UserLogos::where("userId",Input::get("userId"))->update(array("active"=>0));
				$userlogo->active = 1;
			}

		
			$userlogo->save();
			
			

			return $this::responseJson(Messages::showControlPanel("UserLogoCreated"));	
		}
	}

	public function _show($equipment)
	{
		//
		return UserLogos::find($equipment);
	}

	public function _update($id)
	{
		ini_set('max_execution_time', 3000);
        set_time_limit(3000);
		$validation = UserLogos::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$userlogo = UserLogos::find($id);
			$userlogo->userId = Input::get("userId");


				
			if(Input::hasFile("image1")) {
				$images = Helper::saveImage(Input::file("image1"),Config::get("constants.moreExercises"));
				$userlogo->image = $images["image"];
				$userlogo->thumb = $images["thumb"];
			}

			if(Input::has("active")){
				UserLogos::where("userId",Input::get("userId"))->update(array("active"=>0));
				$userlogo->active = 1;
			}

		
			$userlogo->save();

			

			

			return $this::responseJson(Messages::showControlPanel("UserLogoModified"));	
			
		}
	}

	function rotateRight(){
		$id = Input::get("id");
		$obj = UserLogos::find($id);
		
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(-90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateLeft(){
		$id = Input::get("id");
		$obj = UserLogos::find($id);
	
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateRight1(){
		$id = Input::get("id");
		$obj = UserLogos::find($id);
		
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(-90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(-90);
				$image->save();
			}

			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}

	function rotateLeft1(){
		$id = Input::get("id");
		$obj = UserLogos::find($id);
	
		if($obj){
			if(file_exists($obj->image)){
				$image = Image::make($obj->image);
				$image->rotate(90);
				$image->save();
				$image = Image::make($obj->thumb);
				$image->rotate(90);
				$image->save();
			}


			return $this::responseJson(Lang::get("messages.ImageRotated"));
		}
	}


	

	public function _destroy($id)
	{
		//
		$equipment = UserLogos::find($id);
		$equipment->delete();
		return $this::responseJson(Messages::showControlPanel("UserLogoDeleted"));
	}

}