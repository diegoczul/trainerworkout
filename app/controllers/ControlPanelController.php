<?php

class ControlPanelController extends \BaseController {

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

		
	}

	public function indexErrors(){
		$contents = File::get(storage_path()."/logs/laravel.log");
		return View::make("ControlPanel.errors")
			->with("contents",$contents);
	}

	public function indexErrorsReset(){

		$f = @fopen(storage_path()."/logs/laravel.log","r+");
		if($f !== false){
			ftruncate($f,0);
			fclose($f);
		}
		return Redirect::route("ControlPanelErrors");
	}

	

}