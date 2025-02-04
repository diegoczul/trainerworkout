<?php

class TestimonialsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /testimonials
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
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.testimonials")
			->with("testimonials",Testimonials::with("fUser")->with("user")->where("userId","=",$userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Testimonials::where("userId","=",$userId)->count());
	}

	public function indexFull()
	{

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
		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		$testimonials = null;
		$testimonialsCount = 0;

		if($userId != Auth::user()->id){
			$testimonials = Testimonials::with("fUser")->with("user")->where("userId","=",$userId)->orderBy('updated_at', 'DESC')->whereNotNull("approved")->take($this->pageSize)->get();
			$testimonialsCount = Testimonials::where("userId","=",$userId)->whereNotNull("approved")->count();
		} else {
			$testimonials = Testimonials::with("fUser")->with("user")->where("userId","=",$userId)->orderBy('updated_at', 'DESC')->take($this->pageSize)->get();
			$testimonialsCount = Testimonials::where("userId","=",$userId)->count();
		}

		return View::make("widgets.full.testimonials")
			->with("testimonials",$testimonials)
			->with("permissions",$permissions)
			->with("total",$testimonialsCount);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /testimonials/create
	 *
	 * @return Response
	 */

	public function approveTestimonial(){
		$user = Auth::user();

		$status = Input::get("status");
		$testimonialId = Input::get("id");

		$testimonial = Testimonials::find($testimonialId);

		if($status == "approve"){
			$testimonial->approved = date("Y-m-d H:i:s");
			$testimonial->save();
			return $this::responseJson(Lang::get("messages.TestimonialApproved"));
		} else {
			$testimonial->approved = null;
			$testimonial->save();
			return $this::responseJson(Lang::get("messages.TestimonialNotApproved"));
		}


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
		$user = Auth::user();
		$userId = $user->id;

		$permissions = null;


		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]) $userId = Input::get("userId");
			$userId = Input::get("userId");

		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


		$validation = Testimonials::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$testimonials = new Testimonials;
			$testimonials->rating = Input::get("rating");
			$testimonials->testimonial = Input::get("testimonial");
			$testimonials->userId = $userId;
			$testimonials->fromUser = Auth::user()->id;
			$testimonials->save();

			if($testimonials->userId != $testimonials->fromUser){
					Notifications::insertDynamicNotification("NewTestimonial",$userId,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName),true);
			}
			$friend = Users::find($userId);
			if($friend){
				Feeds::insertDynamicFeed("NewTestimonial",$user->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$friend->firstName,"friendLastName"=>$friend->lastName));
			}	
			
			return $this::responseJson(Lang::get("messages.TestimonialAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /testimonials
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /testimonials/{id}
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
	 * GET /testimonials/{id}/edit
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
	 * PUT /testimonials/{id}
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
	 * DELETE /testimonials/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Testimonials::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeleteTestimonial",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.DeleteTestimonial"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}