<?php

class ExercisesImagesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /demomodel
	 *
	 * @return Response
	 */
	public function index($exerciseId)
	{
		$images = ExercisesImages::where("exerciseId",$exerciseId)
			->where(function($query){
				$query->whereNull("userId");
				$query->orWhere("availability","public");
			})->get();
		return $this->responseJson($images);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /demomodel/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /demomodel
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /demomodel/{id}
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
	 * GET /demomodel/{id}/edit
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
	 * PUT /demomodel/{id}
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
	 * DELETE /demomodel/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}