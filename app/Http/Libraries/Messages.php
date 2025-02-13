<?php

class Messages {

    

    

   

    

    

     


    public static function showEmptyMessage($message = "",$self = true){

        $start = "<p class='emptyMessage'>";
        $end = "</p>";
        if(!$self)
            $message = "NoData";
        if($message == ""){
            $message = "NothingFound";
        } 
            return $start.Lang::get("messages.".$message).$end;
        
    }

    public static function showMessage($message) {
        return Lang::get("messages.".$message);
    }



    public static function showFacebookMessage($message,$gender="male") {
        return Lang::get("messages.".$message); 
    }

    public static function showControlPanel($message) {
        return Lang::get("messages.".$message);
    }

    public static function showMessageOnboarding($message) {
        return Lang::get("messages.".$message);
    }

    public static function feed($message,$gender="male") {
        return Lang::get("messages.".$message);
    }

    public static function notification($message,$gender="male") {
        return Lang::get("messages.".$message);
    }

    public static function showNotification($message,$variables = array()) {
        return Lang::get("messages.".$message);
    }

    public static function showEmailMessage($message) {
        return Lang::get("messages.".$message);   
    }

}

?>