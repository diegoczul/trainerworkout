<?php

class OnBoardingController extends \BaseController {


	public function start(){
		Session::put("onboarding.last","step1");
		Session::put("onboarding.started",true);
		Session::put("onboarding.step1",false);
		Session::put("onboarding.step2",false);
		Session::put("onboarding.step2-1",false);
        Session::put("onboarding.step2-2",false);
        Session::put("onboarding.step2-3",false);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",false);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Auth::user()->demoWeb = null;
		Auth::user()->save();
		Session::put("onboarding.completed",false);
		Session::save();

		return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)));
	}

	public function skipDemo(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",false);
        Session::put("onboarding.step2-2",false);
        Session::put("onboarding.step2-3",false);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",true);
		Session::put("onboarding.step6",true);
		Session::put("onboarding.step7",true);
		Session::put("onboarding.step8",true);
		Auth::user()->demoWeb = date("Y-m-d H:i:s");
		Auth::user()->save();
		Session::put("onboarding.completed",true);
		Session::save();

		UserMessages::where("from",Auth::user()->id)->where("to",Config::get("constants.onboardingUser"))->delete();
		UserMessages::where("to",Auth::user()->id)->where("from",Config::get("constants.onboardingUser"))->delete();

		return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)))->with("message",Lang::get("messages.Welcome"));
	}

	public function step2(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",false);
        Session::put("onboarding.step2-2",false);
        Session::put("onboarding.step2-3",false);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",false);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('TrainerProfile');
	}

	public function step21(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",false);
        Session::put("onboarding.step2-3",false);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",false);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('EditProfileTrainer');
	}

	public function step22(){
		Session::put("onboarding.started",true);
		Session::put("onboarding.completed",false);
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",false);
        Session::put("onboarding.step2-3",false);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",false);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('TrainerProfile');
	}


	public function step23(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('TrainerProfile');
	}

	public function step3(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",false);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('TrainerProfile');
	}

	public function step4(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",false);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route("TrainerFriends");
	}

	public function step41(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::to("/Client/24/alaintrainee");
	}

	public function step5(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::to("/Client/24/alaintrainee");
	}

	public function step6(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",false);
		Session::put("onboarding.step6",false);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::to('/Trainer/Workouts');
	}

	public function step7(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",true);
		Session::put("onboarding.step6",true);
		Session::put("onboarding.step7",false);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::to('/Trainer/CreateWorkout');
	}

	public function step8(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",true);
		Session::put("onboarding.step6",true);
		Session::put("onboarding.step7",true);
		Session::put("onboarding.step8",false);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::to('/Trainer/Workouts');
	}

	public function step9(){
		Session::put("onboarding.step1",true);
		Session::put("onboarding.step2",true);
		Session::put("onboarding.step2-1",true);
        Session::put("onboarding.step2-2",true);
        Session::put("onboarding.step2-3",true);
		Session::put("onboarding.step3",true);
		Session::put("onboarding.step4",true);
		Session::put("onboarding.step4-1",true);
		Session::put("onboarding.step5",true);
		Session::put("onboarding.step6",true);
		Session::put("onboarding.step7",true);
		Session::put("onboarding.step8",true);
		Session::put("onboarding.step9",false);
		Session::save();
		return Redirect::route('Trainer', array('userName' => Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName)));
	}

	public function messageChat($messageToSend){
		
		$message = new UserMessages();
		$message->message = Messages::showMessageOnboarding($messageToSend);
		$message->from = Config::get("constants.onboardingUser");
		$message->to = Auth::user()->id;
		$message->save();

	}

}