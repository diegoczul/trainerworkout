<?php

class OrdersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /orders
	 *
	 * @return Response
	 */
	public function index()
	{
		
		$user = Auth::user();
	
		
		
		return View::make("Store.cart")
			->with("user",$user)
			->with("cart",Session::get("cart"));
	}

	public function thankyou()
	{
		
		return View::make("thankyou");
	}

	public function checkout()
	{

		$debug = Config::get('app.debug');
		
		//dd(Session::get("cart"));

		if(!Auth::check()){
			Session::put("redirect","StoreCheckout");
			Session::save();
			//$url = URL::route("home",array("#freeTrialSignup"));
			return Redirect::route("StoreCreateAccount")->with("message",Lang::get("messages.FirstCreateAnAccount"));
		}


		if(count(Session::get("cart")["items"]) == 0){
			if(Auth::user()->userType == "Trainer"){
				return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.CartEmpty"));
			} else {
				return Redirect::route('Trainee', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.CartEmpty"));
			}	
		}

		return View::make("Store.checkout")
			->with("debug",$debug)
			->with("cart",Session::get("cart"));
	}

	public function createAccount(){
		return View::make("Store.createAccountStore");
	}

	public function indexPaypage($package="")
	{
		$array = explode("?",Request::fullURL());
		
		if(count($array) > 1){
			Session::put("utm",$array[1]);
		}
		return View::make("paypage")
		->with("package",$package);
	}

	public function processPaymentNoLogin($ignoreThis=""){

		/// DEBUG FLAG FOR PAYMENTS ///
		$debug = Config::get('app.debug');
		/// ---------------------- ///


		$validation = Validator::make(Input::all(), array("email"=>"email|required","firstname"=>"required","lastname"=>"required", "street"=>"required", "city"=>"required", "country"=>"required", "province"=>"required","password"=>"required"));
		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}
		if(Users::where("email",Input::get("email"))->count() > 0){
			$user = Users::where("email",Input::get("email"))->first();
		} else {
			$user = new Users();
			$user->firstName = Input::get("firstname");
			$user->lastName = Input::get("lastname");
			$user->email = Input::get("email");
			$user->userType = "Trainer";
			$user->city = Input::get("city");
			$user->province = Input::get("province");
			$user->country = Input::get("country");
			$user->street = Input::get("street");
			$user->postalCode = Input::get("postalcode");
			$user->password = Hash::make(Input::get("password"));
			$user->save();

			$user->sendActivationEmail();

			Auth::loginUsingId($user->id);
		}


		
		$membership = Memberships::find(Input::get("pay_num_of_accounts"));
		$subId = "";
		
		if($membership->free == 0){
			//CHECKOUT PART
			//$debug = true;
			if($debug){
				Stripe::setApiKey(Config::get("constants.STRIPETestsecret_key"));
			} else {
				Stripe::setApiKey(Config::get("constants.STRIPEsecret_key"));
			}
			
			
			try {
				
				
				$token = $_POST['stripeToken'];

				if($user->stripeCheckoutToken == ""){
					$customer = \Stripe\Customer::create(array(
					  "source" => $token,
					  "description" => $user->email,
					  "email" => $user->email)
					);

				
					$user->stripeCheckoutToken = $customer->id;
					$user->fourLastDigits = $customer->sources->data[0]->last4;
					$user->typeOfCreditCard = $customer->sources->data[0]->brand;
					$user->save();

				}
				// Get the credit card details submitted by the form
			} catch(Exception $e) {
				//dd($e->message);
			  return Redirect::back()->withInput()->withErrors(Lang::get("messages.CreditDeclined"));
			}
			
			try {
					
					$customer = \Stripe\Customer::retrieve($user->stripeCheckoutToken);
					if($subscription = $customer->subscriptions->total_count == 0){
						$subscription = $customer->subscriptions->create(array("plan" => $membership->idAPI));
						$subId = $subscription->id;
					} else {
					
						$subscription = $customer->subscriptions->retrieve($customer->subscriptions->data[0]->id);
						$subscription->plan = $membership->idAPI;
						$subscription->save();
						$subId = $subscription->id;
					}
			} catch(Exception $e) {
				
			  	return Redirect::back()->withInput()->withErrors(Lang::get("messages.ProblemCheckout"));
			} 

		}

		MembershipsUsers::where("userId",$user->id)->where("id","!=",$membership->id)->delete();
		
		$itemPurchased = $membership;
				
		$membership = new MembershipsUsers;
		$membership->userId = $user->id;
		//DEFAULT MEMBERSHIP
		$membership->membershipId = $itemPurchased->id;
		$membership->registrationDate = date("Y-m-d H:i:s");
		if($itemPurchased->durationType == "monthly"){
			$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 month'));
		} else {
			$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
		}
		$membership->subscriptionStripeKey = $subId;
		$membership->save();

		//OrderItems::where("orderId",$cart["orderId"])->where("itemType","=","Membership")->update(array("paid"=>date("Y-m-d H:i:s"),"subscriptionStripeKey"=>$subId));
		
		return Redirect::route("thankyouPayment");
		
	}



	public function stripeAPISingleCheckout($total,$debug = false){

		$user = Auth::user();
		$cart = Session::get("cart");

		if($debug){
			Stripe::setApiKey(Config::get("constants.STRIPETestsecret_key"));
		} else {
			Stripe::setApiKey(Config::get("constants.STRIPEsecret_key"));
		}
		
		if(Input::get("oldCustomer") == ""){
			try {
				$token = $_POST['stripeToken'];
				
				if($user->stripeCheckoutToken == ""){
					$customer = \Stripe\Customer::create(array(
					  "source" => $token,
					  "description" => Auth::user()->email,
					  "email" => Auth::user()->email)
					);

					$user->stripeCheckoutToken = $customer->id;
					$user->save();
				}
			} catch(Exception $e) {
				return $e->getMessage();
				return Lang::get("messages.ProblemCheckout");
			} 
		}
		try{
		// Create the charge on Stripe's servers - this will charge the user's card
			$charge = null;
		if(Input::get("oldCustomer") == ""){
			$charge = \Stripe\Charge::create(array(
			  "amount" => $cart["total"]*100, // amount in cents, again
			  "currency" => "usd",
			  "source" => $token,
			  "description" => Auth::user()->email,
			  "email" => Auth::user()->email)
			);
		} else {
			$charge = \Stripe\Charge::create(array(
			  "amount" => $cart["total"]*100, // amount in cents, again
			  "currency" => "usd",
			  "customer" => Auth::user()->stripeCheckoutToken,
			  "description" => Auth::user()->email,
			  "email" => Auth::user()->email)
			);
		}
			$items = OrderItems::where("orderId",$cart["orderId"])->where(function($query){ 
										$query->orWhere("itemType","=","Workout"); 
										$query->orWhere("itemType","=","Session"); 			})->update(array("paid"=>date("Y-m-d H:i:s")));
			
			$user->fourLastDigits = $charge->source->last4;
			$user->typeOfCreditCard = $charge->source->brand;
			$user->save();
			return "";
		} catch(Exception $e) {
		  	return $e->getMessage();
		} 
	}


	public function stripeAPIMembershipCheckout($membership,$order = "",$debug = false){
		$user = Auth::user();
		$cart = Session::get("cart");
		if($debug){
			Stripe::setApiKey(Config::get("constants.STRIPETestsecret_key"));
		} else {
			Stripe::setApiKey(Config::get("constants.STRIPEsecret_key"));
		}
		
		if(Input::get("oldCustomer") == ""){
			try {
				
				
				$token = $_POST['stripeToken'];

				if($user->stripeCheckoutToken == ""){
					$customer = \Stripe\Customer::create(array(
					  "source" => $token,
					  "description" => Auth::user()->email,
					  "email" => Auth::user()->email)
					);

					$user->stripeCheckoutToken = $customer->id;
					$user->fourLastDigits = $customer->sources->data[0]->last4;
					$user->typeOfCreditCard = $customer->sources->data[0]->brand;
					$user->save();

				}
				// Get the credit card details submitted by the form
			} catch(Exception $e) {
			  return Lang::get("messages.ProblemCheckout");
			}
		}

		try {
			
				$subId = "";
				$customer = \Stripe\Customer::retrieve($user->stripeCheckoutToken);

				if($subscription = $customer->subscriptions->total_count == 0){
					$subscription = $customer->subscriptions->create(array("plan" => $membership));
					$subId = $subscription->id;
				} else {
				
					$subscription = $customer->subscriptions->retrieve($customer->subscriptions->data[0]->id);
					$subscription->plan = $membership;
					$subscription->save();
					$subId = $subscription->id;
				}
				
			MembershipsUsers::where("userId",Auth::user()->id)->delete();
			
			OrderItems::where("orderId",$cart["orderId"])->where("itemType","=","Membership")->update(array("paid"=>date("Y-m-d H:i:s"),"subscriptionStripeKey"=>$subId));
			//$order = Orders::find($cart["orderId"]);
			// return View::make("Store.thankYou")
			// 		->with("message",Lang::get("messages.CheckoutComplete"))
			// 		->with("user",$user)
			// 		->with("order",$order);		

		} catch(Exception $e) {
		  	return $e->getMessage();
		} 


	}

	public function stripeCancelUserMembership($membership,$order = "",$debug = false){
		$user = Auth::user();
		$cart = Session::get("cart");
		if($debug){
			Stripe::setApiKey(Config::get("constants.STRIPETestsecret_key"));
		} else {
			Stripe::setApiKey(Config::get("constants.STRIPEsecret_key"));
		}

		if(Input::get("oldCustomer") != ""){
			try {
				
					$subId = "";
					$customer = \Stripe\Customer::retrieve($user->stripeCheckoutToken);

					if($subscription = $customer->subscriptions->total_count == 0){

					} else {
						$subscription = $customer->subscriptions->retrieve($customer->subscriptions->data[0]->id);
						$subscription->cancel();
					}
					
				MembershipsUsers::where("userId",Auth::user()->id)->delete();
				
				OrderItems::where("orderId",$cart["orderId"])->where("itemType","=","Membership")->update(array("paid"=>date("Y-m-d H:i:s"),"subscriptionStripeKey"=>""));
	

			} catch(Exception $e) {
			  	return $e->getMessage();
			} 
		} else {
			MembershipsUsers::where("userId",Auth::user()->id)->delete();
		}
	}



	public function processPayment(){

		//Session::forget('cart');
			$user = Auth::user();
			$order = null;
			if( Session::has("cart")){
				$cart = Session::get("cart");

				$order = null;
				if(!array_key_exists("orderId",$cart)) $cart["orderId"] = 0;
				if($cart["orderId"] == ""){
					$order = new Orders();
				} else{
					$order = Orders::find($cart["orderId"]);
				}
				$order->userId = Auth::user()->id;
				$order->total = $cart["total"]; 
				$order->subtotal = $cart["subtotal"];
				$order->street = Input::get("street");
				$order->city = Input::get("city");
				$order->province = Input::get("province");
				$order->country = Input::get("country");
				$order->postalcode = Input::get("postalcode");
				$order->orderDate = date("Y-m-d H:i:s");

				$order->paidBy = "Paypal";
				$order->status = "Unpaid";
				$order->currency = "USD";

				$order->save();
				$cart["orderId"] = $order->id;

				Session::put("cart",$cart);
				//Session::save();

				$totalSinglePurchase = 0;
				$membershipPurchase = 0;
				$membership = "";
				for($x = 0; $x  < count($cart["items"]); $x++){
					$item = $cart["items"][$x];
					$orderItem = null;
					if($item["orderItemId"] != "") {
						$orderItem = OrderItems::find($item["orderItemId"]);
					} else {
						$orderItem = new OrderItems();
					}
				
					if($item["type"] == "Workout"){

						$itemPurchased = Workouts::find($item["id"]);
						$orderItem->itemType = "Workout";
						$totalSinglePurchase += $itemPurchased->price;

					} elseif($item["type"] == "Session"){
						$itemPurchased = TrainerSessions::find($itemPurchased->id);
						$orderItem->itemType = "Session";
						$totalSinglePurchase += $itemPurchased->price;
					} else {

						$itemPurchased = Memberships::find($item["id"]);
						$orderItem->itemType = "Membership";
						$membershipPurchase += $itemPurchased->price;
						$membership = $itemPurchased->id;
					}
					
					$orderItem->orderId = $order->id;
					$orderItem->itemId = $item["id"];
					$orderItem->paid = 0;
					
					$orderItem->quantity = 1;
					$orderItem->price = $itemPurchased->price;
					$orderItem->save();
					$item["orderItemId"] = $orderItem->id;
					$cart["items"][$x] = $item;

					
				}
				Session::put("cart",$cart);
				//Session::save();

				$approvedSingle = false;
				$approvedMembership = false;

				/// DEBUG FLAG FOR PAYMENTS ///
				$debug = Config::get('app.debug');
				/// ---------------------- ///



				if($totalSinglePurchase >0){
					$result = $this->stripeAPISingleCheckout($totalSinglePurchase,$debug);
					if($result == "") $approvedSingle = true;
					if($result != "") return Redirect::back()->withErrors($result);
				} else {
					$approvedSingle = true;
				}

				if($membershipPurchase >0){
					$mem = Memberships::find($membership);
					$result = $this->stripeAPIMembershipCheckout($mem->idAPI,$order,$debug);
					if($result == "") $approvedMembership = true;
					if($result != "") return Redirect::back()->withErrors($result);
				} else {
					$approvedMembership = true;
				}

				if($approvedSingle && $approvedMembership){
					for($x = 0; $x  < count($cart["items"]); $x++){
						$item = $cart["items"][$x];
						$orderItem = OrderItems::find($item["orderItemId"]);
							if($item["type"] == "Workout"){
								$itemPurchased = Workouts::find($item["id"]);
								$workoutNew = new Workouts();
								$workoutNew->name = $itemPurchased->name;
								$workoutNew->shares = 0;
								$workoutNew->views = 0;
								$workoutNew->timesPerformed = 0;
								$workoutNew->objectives = $itemPurchased->objectives;
								$workoutNew->userId = Auth::user()->id;
								$workoutNew->authorId = $itemPurchased->authorId;
								$workoutNew->availability = "private";
								$workoutNew->save();
								$itemPurchased->shares++;
								$itemPurchased->save();

								$WorkoutsExercises = WorkoutsExercises::where("workoutId",$itemPurchased->id)->get();
								foreach($WorkoutsExercises as $workoutExercise){
									$workoutExerciseNew = new WorkoutsExercises();
									$workoutExerciseNew = $workoutExercise->replicate();
									$workoutExerciseNew->workoutId = $workoutNew->id;
									$workoutExerciseNew->save();

									$templateSets = TemplateSets::where("workoutsExercisesId",$workoutExercise->id)->get();

									foreach($templateSets as $templateSet){
										$templateSetNew = new TemplateSets;
										$templateSetNew = $templateSet->replicate();
										$templateSetNew->workoutId = $workoutNew->id;
										$templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
										$templateSetNew->save();
									}
								}

								$workoutNew->createSets();

								Event::fire('addedAWorkoutMarket', array(Auth::user(),$itemPurchased->id,$itemPurchased->price));
							}

							if($item["type"] == "Session"){

							
								$itemPurchased = TrainerSessions::find($item["id"]);
								if(!Clients::checkIfTrainerHasClient(Auth::user()->id)){
									$client = new Clients();
									$client->userId = Auth::user()->id;
									$client->trainerId = $itemPurchased->userId;
									$client->approvedClient = 1; 
									$client->approvedTrainer = 1; 
									$client->save();

									Notifications::insertDynamicNotification(Messages::notifications("SessionClient"),$itemPurchased->userId,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName),true);
								}
									for($x = 0; $x < $itemPurchased->numberOfSessions; $x++){
										$session = new SessionsUsers;
										$session->userId = Auth::user()->id;
										$session->trainerId = $itemPurchased->userId;
										$session->orderItemId = $orderItem->id;
										$session->type = "Session";
										$session->save();
									}
									Notifications::insertDynamicNotification(Messages::notifications("SessionBought"),$mem->userId,Auth::user()->id,array("firstName"=>Auth::user()->firstName,"lastName"=>Auth::user()->lastName, "sessions"=>$mem->numberOfSessions),true);

									
							}

							if($item["type"] == "Membership"){

								$itemPurchased = Memberships::find($item["id"]);

								if($itemPurchased->free == 1) $this->stripeCancelUserMembership($itemPurchased->idAPI,$order,$debug);

								$membership = new MembershipsUsers;
								$membership->userId = Auth::user()->id;
								//DEFAULT MEMBERSHIP
								$membership->membershipId = $itemPurchased->id;
								$membership->registrationDate = date("Y-m-d H:i:s");
								if($itemPurchased->durationType == "monthly"){
									$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 month'));
								} else {
									$membership->expiry = date('Y-m-d H:i:s', strtotime('+1 year'));
								}
								$membership->subscriptionStripeKey = $orderItem->subscriptionStripeKey;
								$membership->orderItemId = $orderItem->id;
								$membership->save();
									
							}
						}
				} else {
					return Redirect::back()->withInput()->withError(Lang::get("messages.ProblemCheckout"));
				}

				Session::forget('cart');

				return View::make("Store.thankYou")
					->with("message",Lang::get("messages.CheckoutComplete"))
					->with("user",$user)
					->with("order",$order);		
			} else {

				return Redirect::route(Auth::user()->userType,array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->withError(Lang::get("messages.NotFound"));
			}


			
	}

	public function removeFromCart($identifier){
		$cart = Session::get("cart");
		$index = -1;
		foreach($cart["items"] as $item){
			if($identifier == $item["identifier"]) $index = $item["identifier"];
		}
		if(array_key_exists($index,$cart["items"])) {
			array_splice($cart["items"],$index, 1);
			Session::set("cart",$cart);
			return Redirect::route("cart")->with("message",Lang::get("messages.ItemRemovedFromCart"));
		} else {
			return Redirect::route("cart")->withError(Lang::get("messages.NotFound"));
		}		
	}

	public function nextIdentifier($cart){
		$max = 0;

		if(count($cart["items"]) == 0) return 1;
		foreach($cart["items"] as $item){

			if($max < $item["identifier"]) $max = $item["identifier"];
		}
		return $max+1;
	}

	public function upgradePlan(){
		$user = Auth::user();
		return View::make("Store.upgradePlan")
			->with("user",$user)
			->with("cart",Session::get("cart"));
	}

	public function emptyCart(){
		$cartObject = array(
									"items"=>array(),
									"orderId"=>0,
									"quantity"=>0,
									"provincialTax"=>0,
									"federalTax"=>0,
									"subtotal"=>0,
									"total"=>0
								);
		Session::put("cart",$cartObject);
		Session::save();
	}

	public function addToCart($workoutId,$type){
		$user = Auth::user();
		$cartObject = array(
									"items"=>array(),
									"orderId"=>0,
									"quantity"=>0,
									"provincialTax"=>0,
									"federalTax"=>0,
									"subtotal"=>0,
									"total"=>0
								);
		if(Session::has("cart") == false) Session::put("cart",$cartObject);
	
		///ONLY TEMPORARY
		$this->emptyCart();

		$cart = Session::get("cart");

		$cartObject["identifier"] = $this->nextIdentifier($cart);
		if($type == "Workout"){
			
			$workout = Workouts::find($workoutId);
			
			$cartObject["id"] = $workoutId;
			$cartObject["orderItemId"] = null;
			$cartObject["type"] = "Workout";
			if($workout){
				array_push($cart["items"],$cartObject);
				$cart["quantity"]++;
				$cart["subtotal"] += $workout->price; 
				$cart["total"] += $workout->price; 
				Session::put("cart",$cart);
				Session::save();
				return Redirect::route("StoreCheckout");
			} else {
				return Redirect::route("cartUpgradePlan")->withError(Lang::get("messages.NotFound"));
			}
		} elseif($type == "Session"){
			
			$workout = TrainerSessions::find($workoutId);
			
			$cartObject["orderItemId"] = null;
			$cartObject["id"] = $workoutId;
			$cartObject["type"] = "Session";
		
			if($workout){
				array_push($cart["items"],$cartObject);
				$cart["quantity"]++;
				$cart["subtotal"] += $workout->price; 
				$cart["total"] += $workout->price; 
				Session::put("cart",$cart);
				Session::save();
				return Redirect::route("StoreCheckout");
			} else {
				return Redirect::route("cartUpgradePlan")->withError(Lang::get("messages.NotFound"));
			}

		} else {

			
			$workout = Memberships::find($workoutId);
			$cartObject["id"] = $workoutId;
			$cartObject["orderItemId"] = null;
			$cartObject["type"] = "Membership";
		
			if($workout){
				array_push($cart["items"],$cartObject);
				$cart["quantity"]++;
				$cart["subtotal"] += $workout->price; 
				$cart["total"] += $workout->price; 

				Session::put("cart",$cart);
				Session::save();
			
				// return View::make("Store.cart")
				// ->with("user",$user)
				// ->with("cart",Session::get("cart"));

				return Redirect::route("StoreCheckout");
			} else {
				return Redirect::route("cartUpgradePlan")->withError(Lang::get("messages.NotFound"));
			}
		}

	}


	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /orders
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /orders/{id}
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
	 * GET /orders/{id}/edit
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
	 * PUT /orders/{id}
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
	 * DELETE /orders/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}