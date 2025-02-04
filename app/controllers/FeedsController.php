<?php

class FeedsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /clients
	 *
	 * @return Response
	 */

	public $pageSize = 8;
	public $pageSizeFull = 8;


	public function indexClients()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;

		$clientList = Clients::where("trainerId",$userId)->lists("userId");
		if(empty($clientList)){
			$clientList = array(0);
		}

		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$clientList = array(Input::get("userId"));
				if(empty($clientList)){
					$clientList = array(0);
				}
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


		

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.feedClient")
			->with("feeds",
						DB::table("feeds")->select("feeds.id as feedId","message","userId","feeds.created_at as date","fromId","action","link","type",DB::raw("'feed' as sourceType"),"users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("notifications.id as feedId","message","userId","notifications.created_at as date","fromId","action","link","type",DB::raw("'notification' as sourceType"),"users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",count(DB::table("feeds")->select("message","userId","feeds.created_at as date","fromId","action","link","users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("message","userId","notifications.created_at as date","fromId","action","link","users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->get()));
	}

	public function indexClientsFull()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;

		$clientList = Clients::where("trainerId",$userId)->lists("userId");
		if(empty($clientList)){
			$clientList = array(0);
		}

		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$clientList = array(Input::get("userId"));
				if(empty($clientList)){
					$clientList = array(0);
				}
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


		

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.feedClients")
			->with("feeds",
						DB::table("feeds")->select("feeds.id as feedId","message","userId","feeds.created_at as date","fromId","action","link","type",DB::raw("'feed' as sourceType"),"users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("notifications.id as feedId","message","userId","notifications.created_at as date","fromId","action","link","type",DB::raw("'notification' as sourceType"),"users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",count(DB::table("feeds")->select("message","userId","feeds.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("message","userId","notifications.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->get()));
	}

	public function indexClient()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;

		$clientList = array(Input::get("userId"));

		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$clientList = array(Input::get("userId"));
				if(empty($clientList)){
					$clientList = array(0);
				}
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}


		

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.feedClients")
			->with("feeds",
						DB::table("feeds")->select("feeds.id as feedId","message","userId","feeds.created_at as date","fromId","action","link","type",DB::raw("'feed' as sourceType"),"users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("notifications.id as feedId","message","userId","notifications.created_at as date","fromId","action","link","type",DB::raw("'notification' as sourceType"),"users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",count(DB::table("feeds")->select("message","userId","feeds.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("message","userId","notifications.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->get()));
	}

	public function indexClientFull()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;

		$clientList = array(Input::get("userId"));

		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
				$clientList = array(Input::get("userId"));
				if(empty($clientList)){
					$clientList = array(0);
				}
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,null);
		}

		$clientId = Input::get("userId");


		

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.feedClient")
			->with("feeds",
						DB::table("feeds")->select("feeds.id as feedId","message","userId","feeds.created_at as date","fromId","action","link","type",DB::raw("'feed' as sourceType"),"users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("notifications.id as feedId","message","userId","notifications.created_at as date","fromId","action","link","type",DB::raw("'notification' as sourceType"),"users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("clientId",$clientId)
			->with("total",count(DB::table("feeds")->select("message","userId","feeds.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","userId")->whereIn("userId",$clientList)->whereNull("archived_at")
				->union(DB::table("notifications")->select("message","userId","notifications.created_at as date","fromId","action","users.*")->leftJoin("users","users.id","=","fromId")->whereIn("fromId",$clientList)->where("display","!=","top")->whereNull("archived_at"))
				->orderBy('date', 'DESC')->get()));
	}



	/**
	 * Show the form for creating a new resource.
	 * GET /clients/create
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
		$validation = Users::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			$clients = new Users;
			$clients->client = Input::get("client");
			$clients->measureable = Input::get("measureable");
			$clients->recordDate = Input::get("dateRecord");
			$clients->userId = Auth::user()->id;
			$clients->save();
			Feeds::insertFeed("NewObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			return $this::responseJson(Lang::get("messages.ObjectiveAdded"));	
		}
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /clients
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /clients/{id}
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
	 * GET /clients/{id}/edit
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
	 * PUT /clients/{id}
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
	 * DELETE /clients/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$obj = Users::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
//			Feeds::insertFeed("DeleteObjective",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
			$obj->delete();
			return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

	public function archive($type,$id)
	{
		if($type == "Feed"){
			$obj = Feeds::find($id);
		} else {
			$obj = Notifications::find($id);
		}
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));
		$obj->archived_at = date("Y-m-d H:i:s");
		$obj->save();
		return $this::responseJson(Lang::get("messages.FeedArchived"));
		
		
	
	}


	public function ControlPanelFeeds(){
		$feeds = Feeds::with("user")->whereNull("reported_at")->orderBy("reported_at","Desc")->get();
		$date = date("Y-m-d");
		$email = Config::get("mail.username");
		Feeds::whereNull("reported_at")->update(array("reported_at"=>date("Y-m-d")));
		Mail::queueOn(App::environment(),'ControlPanel.emails.feeds', array("date"=>$date,"feeds"=>serialize($feeds)), function($message) use ($email,$date)
				{
				  $message->to($email)
		          			->subject("Activity of ".$date);
				});
	}

}