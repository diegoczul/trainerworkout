<?php

class OnBoarding {

    


public static function launchOnboarding($sessionOnboarding){

    if( Auth::user()->demoWeb == ""){
    $sessionOnboarding = self::initializeOnboardingDemo($sessionOnboarding);
    $sessionOnboarding["started"] = true; 
    $package = array("html"=>"","scripts"=>"");
    $currentURL = Request::segment(2);



    if(!$sessionOnboarding["completed"] and Auth::user()->demoWeb == null){


        Auth::user()->doubleCheckOnboardingClient();

            if(1==0){

            } else if(Request::segment(1) == "Workout" and $sessionOnboarding["step7"]){
                $sessionOnboarding["step8"] = true; 
                $sessionOnboarding["step7"] = true; 
                $sessionOnboarding["step1"] = true; 
                $sessionOnboarding["step2"] = true; 
                $sessionOnboarding["step2-1"] = true; 
                $sessionOnboarding["step2-2"] = true; 
                $sessionOnboarding["step2-3"] = true; 
                $sessionOnboarding["step3"] = true; 
                $sessionOnboarding["step4"] = true; 
                $sessionOnboarding["step4-1"] = true; 
                $sessionOnboarding["step5"] = true; 
                $sessionOnboarding["step6"] = true; 
                $sessionOnboarding["step7"] = true; 
                $sessionOnboarding["step8"] = true; 
                $sessionOnboarding["last"] = ""; 
                Auth::user()->demoWeb = date("Y-m-d H:i:s");
                Auth::user()->save();
                $sessionOnboarding["completed"] = true;
              
                return self::step("step8","step8",$sessionOnboarding);
            }
            else if(Request::segment(2) == "Workouts" and $sessionOnboarding["step6"]){
                $sessionOnboarding["step7"] = true; 
                
                return self::step("step7","step7",$sessionOnboarding);
            }
            else if(Request::segment(2) == "CreateWorkout" and $sessionOnboarding["step5"]){
                $sessionOnboarding["step6"] = true; 
                
                return self::step("step6","step6",$sessionOnboarding);
            }
            else if(Request::segment(2) == "Workouts"){
                $sessionOnboarding["step5"] = true; 
                $sessionOnboarding["step4-1"] = true; 
                
                return self::step("step5","step5",$sessionOnboarding);
            }
            else if(Request::segment(1) == "Client" and $sessionOnboarding["step4-1"]){
                $sessionOnboarding["step4-1"] = true; 
                
                return self::step("step4-1","step4-1",$sessionOnboarding);
            }
            else if(Request::segment(1) == "Client" and $sessionOnboarding["step3"]){
                $sessionOnboarding["step4"] = true; 
                
                return self::step("step4","step4",$sessionOnboarding);
            }
            else if($sessionOnboarding["step3"]){
                $sessionOnboarding["step3"] = true; 
                
                return self::step("step3","step3",$sessionOnboarding);
            }
            else if($sessionOnboarding["step2-3"] and $currentURL == "Profile"){
                $sessionOnboarding["step2-3"] = true; 
                $sessionOnboarding["step3"] = true; 

                
                return self::step("step2-3","step2-3",$sessionOnboarding);
            }
            else if(Session::get("onboarding.step2-2")){
                $sessionOnboarding["step3"] = true; 
                
                return self::step("step3","step3",$sessionOnboarding);
            } 
            else if($currentURL == "Profile" and $sessionOnboarding["step2-1"]){
                $sessionOnboarding["step2-2"] = true; 
                
                return self::step("step2-2","step2-2",$sessionOnboarding);
            } else if($currentURL == "EditProfile" and $sessionOnboarding["step2-1"]){
                $sessionOnboarding["step2-1"] = true; ;
                

                return self::step("step2-1","step2-1",$sessionOnboarding);
            } else if(($currentURL == "Profile") and ($sessionOnboarding["last"] == "step1")){

                $sessionOnboarding["step2-1"] = true;
                $sessionOnboarding["step1"] = true; 
                $sessionOnboarding["last"] = "step2";
                return self::step("step2","step2",$sessionOnboarding);


            } else if(!Session::get("onboarding.step2-1")){
                $sessionOnboarding["step1"] = true; 
                $sessionOnboarding["last"] = "step1";

                
                return self::step("step1","step1",$sessionOnboarding);
            } 
            
               
           

    }

        return $package;

    } else {
        return null;
    }
}

public static function initializeOnboardingDemo($sessionOnboarding){

   

    if($sessionOnboarding["started"] == true){
        return $sessionOnboarding;
    } else {
        $sessionOnboarding["step8"] = false; 
        $sessionOnboarding["step7"] = false; 
        $sessionOnboarding["step1"] = false; 
        $sessionOnboarding["step2"] = false; 
        $sessionOnboarding["step2-1"] = false; 
        $sessionOnboarding["step2-2"] = false; 
        $sessionOnboarding["step2-3"] = false; 
        $sessionOnboarding["step3"] = false; 
        $sessionOnboarding["step4"] = false; 
        $sessionOnboarding["step4-1"] = false; 
        $sessionOnboarding["step5"] = false; 
        $sessionOnboarding["step6"] = false; 
        $sessionOnboarding["step7"] = false; 
        $sessionOnboarding["step8"] = false; 
        $sessionOnboarding["last"] = ""; 
        $sessionOnboarding["started"] = true; 
        $sessionOnboarding["completed"] = false; 
        
    }


    return $sessionOnboarding;



}


public static function resetOnboarding($sessionOnboarding){
    Auth::user()->demoWeb == null;
    Auth::user()->save();

    $sessionOnboarding["step8"] = false; 
    $sessionOnboarding["step7"] = false; 
    $sessionOnboarding["step1"] = false; 
    $sessionOnboarding["step2"] = false; 
    $sessionOnboarding["step2-1"] = false; 
    $sessionOnboarding["step2-2"] = false; 
    $sessionOnboarding["step2-3"] = false; 
    $sessionOnboarding["step3"] = false; 
    $sessionOnboarding["step4"] = false; 
    $sessionOnboarding["step4-1"] = false; 
    $sessionOnboarding["step5"] = false; 
    $sessionOnboarding["step6"] = false; 
    $sessionOnboarding["step7"] = false; 
    $sessionOnboarding["step8"] = false; 
    $sessionOnboarding["last"] = ""; 
    $sessionOnboarding["started"] = true;
    $sessionOnboarding["completed"] = false;
    
    return $sessionOnboarding;
}

public static function step($html,$js,$sessionOnboarding){

    $package = array(
            "html"=>View::make("onboarding.html.".$html),
            "scripts"=>View::make("onboarding.js.".$js),
            "sessionOnboarding"=>$sessionOnboarding,
        );

    return $package;
}

public static function demoOn($sessionOnboarding){
     if( Auth::user()->demoWeb == ""){
        $sessionOnboarding = self::initializeOnboardingDemo($sessionOnboarding);
        Session::put("onboarding",$sessionOnboarding);
        Session::save();
            if($sessionOnboarding["started"] and !$sessionOnboarding["completed"]){
                return true;
            } else {
                return false;
            }
        }
    }
}

?>