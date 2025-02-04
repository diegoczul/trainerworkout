<?php

class FriendsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /friends
	 *
	 * @return Response
	 */

	public $pageSize = 9;
	public $pageSizeFull = 9;


	public function index()
	{
		$user = Auth::user();
		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.base.friends")
			->with("friends",Friends::friend()->where("userId",Auth::user()->id)->orWhere("followingId",Auth::user()->id)->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("user",$user)
			->with("total",Friends::friend()->where("userId",Auth::user()->id)->orWhere("followingId",Auth::user()->id)->count());
	}

	public function indexFriends()
	{

		$userId = Auth::user()->id;
		$user = Auth::user();

		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("trainee.friends")
			->with("friends",Friends::where("userId","=",$userId)->orderBy('created_at', 'ASC')->take($this->pageSize)->get())
			->with("user",$user)
			->with("permissions",$permissions)
			->with("total",Friends::where("userId","=",$userId)->count());
	}

	public function indexFriendsTrainer()
	{

		$userId = Auth::user()->id;
		$permissions = null;
		if(Input::has("userId")){
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
			if($permissions["view"]){
				$userId = Input::get("userId");
			}
		} else {
			$permissions = Helper::checkPremissions(Auth::user()->id,Input::get("userId"));
		}

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("trainer.friends")
			->with("friends",Friends::where("userId","=",$userId)->orderBy('created_at', 'ASC')->take($this->pageSize)->get())
			->with("permissions",$permissions)
			->with("total",Friends::where("userId","=",$userId)->count());
	}

	public function indexSuggest()
	{

		$userId = Auth::user()->id;
		$search = Input::get("term");
		

		return $this->responseJson(Friends::where( function($query) use ($userId) { 
					$query->orWhere("userId","=",$userId);
					//$query->orWhere("followingId","=",$userId);
				})
				->leftJoin('users', function($join) {
				      $join->on('users.id', '=', 'followingId');
				    })
				->where(function($query) use ($search)
											 {
											       $query->orWhere('firstName', "like","%".$search."%");
											       $query->orWhere('lastName', "like","%".$search."%");
											       $query->orWhere('email', "like","%".$search."%");
											 })
				->get());

	}

	public function indexFull()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.friends")
			->with("friends",Friends::where("userId","=",$userId)->orderBy('created_at', 'ASC')->take($this->pageSize)->get())
			->with("total",Friends::where("userId","=",$userId)->count());
	}


	public function indexFullTrainer()
	{

		$userId = Auth::user()->id;

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		return View::make("widgets.full.friends")
			->with("friends",Friends::where("userId","=",$userId)->orderBy('created_at', 'ASC')->take($this->pageSize)->get())
			->with("total",Friends::where("userId","=",$userId)->count());
	}


	public function searchFriend(){

		$search = "";
		if(Input::has("search")) $search = Input::get("search");
		

		if(Input::has("pageSize")) $this->pageSize = Input::get("pageSize") + $this->pageSize;

		$searchArray = explode(" ",$search);
		array_push($searchArray,$search);

		return View::make("widgets.full.friendsSearch")
			->with("users",Users::where(function($query) use ($search,$searchArray)
											 {
											 		if(is_array($searchArray)){
											 			foreach($searchArray as $searchItem){
												 			$query->orWhere('firstName', "like","%".$searchItem."%");
													        $query->orWhere('lastName', "like","%".$searchItem."%");
													        $query->orWhere(DB::raw("concat(firstName,' ',lastName)"), "like","%".$searchItem."%");
													        $query->orWhere('email', "like","%".$searchItem."%");
											 			}
											 		} else {
												 			$query->orWhere('firstName', "like","%".$search."%");
													        $query->orWhere('lastName', "like","%".$search."%");
													        $query->orWhere(DB::raw("concat(firstName,' ',lastName)"), "like","%".$search."%");
													        $query->orWhere('email', "like","%".$search."%");
											 		}
											       
											 })->Where("id","!=",Auth::user()->id)->take($this->pageSize)->get())
			->with("total",Users::where(function($query) use ($search,$searchArray)
											 {
											       if(is_array($searchArray)){
											 			foreach($searchArray as $searchItem){
												 			$query->orWhere('firstName', "like","%".$searchItem."%");
													        $query->orWhere('lastName', "like","%".$searchItem."%");
													        $query->orWhere(DB::raw("concat(firstName,' ',lastName)"), "like","%".$searchItem."%");
													        $query->orWhere('email', "like","%".$searchItem."%");
											 			}
											 		} else {
												 			$query->orWhere('firstName', "like","%".$search."%");
													        $query->orWhere('lastName', "like","%".$search."%");
													        $query->orWhere(DB::raw("concat(firstName,' ',lastName)"), "like","%".$search."%");
													        $query->orWhere('email', "like","%".$search."%");
											 		}
											 })->Where("id","!=",Auth::user()->id)->count());
	}

	public function addFriend(){



        
		if(Input::has("followingId")){
			return $this->create();
		}
		if(Input::has("email")){
			$lookForUser = Users::where("email",Input::get("email"))->get();
			if(!$lookForUser->isEmpty()){
				if(!Friends::checkFollower(Auth::user()->id,$lookForUser->id)){
					return $this->createInternal($lookForUser);
				} else {
					return $this::responseJsonError(Lang::get("messages.AlreadyFollowing"));	
				}
			} else {
				$invite = new Invites;
				$invite->userId = Auth::user()->id;
				$invite->email = Input::get("email");
				$invite->key = GUID::generate();
				$invite->type = "TraineeFriendRequest";
				$invite->save();
				$invite->sendInvite();
				//Feeds::insertFeed("InvitedFriend",Auth::user()->id,Auth::user()->firstName,Auth::user()->lastName);
				return $this::responseJson(Lang::get("messages.InvitationSent"));
			}
		}
		if(Input::has("name")){

		}
		return $this::responseJson(Lang::get("messages.NotFound"));	
	}


	/**
	 * Show the form for creating a new resource.
	 * GET /friends/create
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
		
		$validation = Friends::validate(Input::all());
		if($validation->fails()){
			return $this::responseJsonErrorValidation($validation->messages());
		} else {
			if(Friends::where("followingId",Input::get("followingId"))->where("userId",Auth::user()->id)->count() > 0){
				return $this::responseJson(Lang::get("messages.AlreadyFollowing")); 
			}
			$friends = new Friends;
			$friends->followingId = Input::get("followingId");
			$friends->userId = Auth::user()->id;
			$friends->save();
			$userAux = Users::find(Input::get("followingId"));
			if($userAux){
				Notifications::insertDynamicNotification("Following",$userAux->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName));
				Feeds::insertDynamicFeed("Following",Auth::user()->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$userAux->firstName,"friendLastName"=>$userAux->lastName)); 
				Feeds::insertDynamicFeed("BeingFollowed",$userAux->id,Auth::user()->id,array("firstName"=>$userAux->firstName,"lastName"=>$userAux->lastName,"friendFirstName"=>Auth::user()->firstName,"friendLastName"=>Auth::user()->lastName)); 
			}
			
			return $this::responseJson(Lang::get("messages.FriendAdded"));	
		}
	}

	public function createInternal($user)
	{
			$friends = new Friends;
			$friends->followingId = Input::get($user->id);
			$friends->userId = Auth::user()->id;
			$friends->save();
			$userAux = Users::find(Input::get($user->id));
			if($userAux){
				Notifications::insertDynamicNotification("Following",$userAux->id,Auth::user()->id,array("firstName"=>$userAux->firstName,"lastName"=>$userAux->lastName));
				Feeds::insertDynamicFeed("Following",Auth::user()->id,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$userAux->firstName,"friendLastName"=>$userAux->lastName)); 
			}
			return $this::responseJson(Lang::get("messages.FriendAdded"));	
	}


	public function destroy($id)
	{

		$obj = Friends::find($id);
		if(!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

		if($this->checkPermissions($obj->userId,Auth::user()->id)){
			$userAux = Users::find($obj->followingId);
			if($userAux){
			//	Feeds::insertDynamicFeed("NotFollowing",Auth::user()->id,$obj->followingId,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName,"friendFirstName"=>$userAux->firstName,"friendLastName"=>$userAux->lastName));
			} 
			$obj->delete();
			return $this::responseJson(Lang::get("messages.FriendUnFollowed"));
		} else {
			return $this::responseJsonError(Lang::get("messages.Permissions"));
		}
		
	
	}

}