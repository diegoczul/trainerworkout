<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\UserMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Messages;

class OnBoardingController extends BaseController
{
    public function start()
    {
        Session::put("onboarding.last", "step1");
        Session::put("onboarding.started", true);
        Session::put("onboarding.step1", false);
        Session::put("onboarding.step2", false);
        Session::put("onboarding.step2-1", false);
        Session::put("onboarding.step2-2", false);
        Session::put("onboarding.step2-3", false);
        Session::put("onboarding.step3", false);
        Session::put("onboarding.step4", false);
        Session::put("onboarding.step4-1", false);
        Session::put("onboarding.step5", false);
        Session::put("onboarding.step6", false);
        Session::put("onboarding.step7", false);
        Session::put("onboarding.step8", false);
        Auth::user()->demoWeb = null;
        Auth::user()->save();
        Session::put("onboarding.completed", false);
        Session::save();

        return Redirect::route('Trainer', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)]);
    }

    public function skipDemo()
    {
        Session::put("onboarding.step1", true);
        Session::put("onboarding.step2", true);
        Session::put("onboarding.step2-1", false);
        Session::put("onboarding.step2-2", false);
        Session::put("onboarding.step2-3", false);
        Session::put("onboarding.step3", true);
        Session::put("onboarding.step4", true);
        Session::put("onboarding.step4-1", true);
        Session::put("onboarding.step5", true);
        Session::put("onboarding.step6", true);
        Session::put("onboarding.step7", true);
        Session::put("onboarding.step8", true);
        Auth::user()->demoWeb = now();
        Auth::user()->save();
        Session::put("onboarding.completed", true);
        Session::save();

        UserMessages::where("from", Auth::user()->id)->where("to", Config::get("constants.onboardingUser"))->delete();
        UserMessages::where("to", Auth::user()->id)->where("from", Config::get("constants.onboardingUser"))->delete();

        return Redirect::route('Trainer', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
            ->with("message", Lang::get("messages.Welcome"));
    }

    public function step2()
    {
        $this->resetSteps(["step1", "step2"]);
        return Redirect::route('TrainerProfile');
    }

    public function step21()
    {
        $this->resetSteps(["step1", "step2", "step2-1"]);
        return Redirect::route('EditProfileTrainer');
    }

    public function step22()
    {
        Session::put("onboarding.started", true);
        Session::put("onboarding.completed", false);
        $this->resetSteps(["step1", "step2", "step2-1"]);
        return Redirect::route('TrainerProfile');
    }

    public function step23()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3"]);
        return Redirect::route('TrainerProfile');
    }

    public function step3()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3"]);
        return Redirect::route('TrainerProfile');
    }

    public function step4()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3"]);
        return Redirect::route("TrainerFriends");
    }

    public function step41()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4"]);
        return Redirect::to("/Client/24/alaintrainee");
    }

    public function step5()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4"]);
        return Redirect::to("/Client/24/alaintrainee");
    }

    public function step6()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4"]);
        return Redirect::to('/Trainer/Workouts');
    }

    public function step7()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4", "step5", "step6"]);
        return Redirect::to('/Trainer/CreateWorkout');
    }

    public function step8()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4", "step5", "step6", "step7"]);
        return Redirect::to('/Trainer/Workouts');
    }

    public function step9()
    {
        $this->resetSteps(["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4", "step5", "step6", "step7", "step8"]);
        return Redirect::route('Trainer', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)]);
    }

    public function messageChat($messageToSend)
    {
        $message = new UserMessages();
        $message->message = Messages::showMessageOnboarding($messageToSend);
        $message->from = Config::get("constants.onboardingUser");
        $message->to = Auth::user()->id;
        $message->save();
    }

    private function resetSteps($completedSteps)
    {
        $allSteps = ["step1", "step2", "step2-1", "step2-2", "step2-3", "step3", "step4", "step4-1", "step5", "step6", "step7", "step8", "step9"];
        foreach ($allSteps as $step) {
            Session::put("onboarding.$step", in_array($step, $completedSteps));
        }
        Session::save();
    }
}
