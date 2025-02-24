<?php
namespace App\Http\Libraries;

use App\Models\Clients;
use App\Models\Friends;
use App\Models\Permissions;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Guid\Guid;
use Ramsey\Uuid\Uuid;

class Helper {

    public static function seo($file="",$variables=array()){

        $title = "";
        if(Lang::get("seo.".$file."_title",$variables) == "seo.".$file."_title"){
            $title = Lang::get("seo.index_title",$variables);
        } else {
            $title = Lang::get("seo.".$file."_title",$variables);
        }

        $description = "";
        if(Lang::get("seo.".$file."_description",$variables) == "seo.".$file."_description"){
            $description = Lang::get("seo.index_description",$variables);
        } else {
            $description = Lang::get("seo.".$file."_description",$variables);
        }

        $keywords = "";
        if(Lang::get("seo.".$file."_keywords",$variables) == "seo.".$file."_keywords"){
            $keywords = Lang::get("seo.index_keywords",$variables);
        } else {
            $keywords = Lang::get("seo.".$file."_keywords",$variables);
        }


        $metas = array("title"=>$title,"description"=>$description,"keywords"=>$keywords);
        $output = "";
        $output .= "<title>".htmlspecialchars($metas["title"])."</title>\n";
        $output .= "<meta name='description' content='".htmlspecialchars($metas["description"])."'/>\n";
        $output .= "<meta name='keywords' content='".htmlspecialchars($metas["keywords"])."'/>\n";


        return $output;

    }

    public static function replaceWinCompatible($string){

        $output = str_replace(":","_",$string);
        return $output;
    }

    public static function formatURLString($string) {
    	return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    public static function image($image,$video="",$youtube="") {
    	if(file_exists($image)){
    		return $image;
    	} else if($video != "" or $youtube != "" ) {
    		return Config::get("constants.videoPlaceholder");
    	} else {
            return "assets/img/client.png";
        }
    }

    public static function executionTime(){
        ini_set("max_execution_time",280);
        ini_set("memory_limit",-1);
    }

    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public static function translateOverride($viewPath){
        $whitelabel = Config::get("app.whitelabel");
        if($whitelabel != "default"){
            return "whitelabels/".$whitelabel."/".$viewPath;
        } else {
            return $viewPath;
        }
    }

    public static function objectToArray($d) {
     if (is_object($d)) {
     // Gets the properties of the given object
     // with get_object_vars function
     $d = get_object_vars($d);
     }

     if (is_array($d)) {
     /*
     * Return array converted to object
     * Using __FUNCTION__ (Magic constant)
     * for recursive call
     */
     return array_map(__FUNCTION__, $d);
     }
     else {
     // Return array
     return $d;
     }
    }

    public static function text($text,$chars){
        if(strlen($text) > $chars-3){
            return substr($text, 0,$chars-3)."...";
        } else {
            return $text;
        }
    }

    public static function arrayToObject($d) {
         if (is_array($d)) {
         /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
         return (object) array_map(__FUNCTION__, $d);
         }
         else {
         // Return object
         return $d;
         }
     }

    public static function date($date){
        $dateTime = new DateTime($date, new DateTimeZone(Config::get('app.timezone')));
        if (Auth::check()){
            if(Auth::user()->timezone == ""){
                Auth::user()->timezone = "America/New_York";
                Auth::user()->save();
            }
            $dateTime->setTimezone(new DateTimeZone(Auth::user()->timezone));
        }
        //return $dateTime->format('Y-m-d'); // for example;
        return $dateTime->format('M j, Y'); // for example;
    }

    public static function toDate($date){
        $dateTime = new DateTime($date, new DateTimeZone(Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != ""){
            $dateTime = new DateTime($date, new DateTimeZone(Auth::user()->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone(Config::get('app.timezone')));

        return $dateTime->format('Y-m-d'); // for example;
    }

    public static function toDateTime($date){
        $dateTime = new DateTime($date, new DateTimeZone(Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != ""){
            $dateTime = new DateTime($date, new DateTimeZone(Auth::user()->timezone));
        }
        $dateTime->setTimezone(new DateTimeZone(Config::get('app.timezone')));

        return $dateTime->format('Y-m-d H:i:s'); // for example;
    }


    public static function dateTime($date){
        //return date("yy-m-d",strtotime($date))->timezone(Auth::user()->timezone);
        $dateTime = new DateTime($date, new DateTimeZone(Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != ""){
            $dateTime->setTimezone(new DateTimeZone(Auth::user()->timezone));
        }
        return $dateTime->format('M j, Y, g:i a'); // for example;
    }

    public static function days($date){
        //return date("yy-m-d",strtotime($date))->timezone(Auth::user()->timezone);
        $dateTime = new DateTime($date, new DateTimeZone(Config::get('app.timezone')));
        $today = new DateTime(date("Y-m-d h:i:s"), new DateTimeZone(Config::get('app.timezone')));
        if (Auth::check() and Auth::user()->timezone != ""){
            $dateTime->setTimezone(new DateTimeZone(Auth::user()->timezone));
            $today->setTimezone(new DateTimeZone(Auth::user()->timezone));
        }

        $diff = $dateTime->diff($today)->format("%a");
        return $diff;
    }

    public static function extractYoutubeTag($url){
            if (strpos($url,'v=') !== false) {
                $my_array_of_vars = array();
                parse_str( parse_url( $url, PHP_URL_QUERY ), $my_array_of_vars );
                return $my_array_of_vars['v'];
            } else {
                return "";
            }

    }

    public static function formatPrice($price){
        $price = number_format($price,2);
        return "$".$price;
    }

    public static function formatWeight($weight){
        return number_format($weight, 1, '.', '');
    }

    public static function checkPremissions($viewer,$toView,$toWhat=""){
        $defaultPermissions["view"] = true;
        $defaultPermissions["add"] = true;
        $defaultPermissions["edit"] = true;
        $defaultPermissions["delete"] = true;
        $defaultPermissions["self"] = true;
        if($toView == null) return $defaultPermissions;
        if($viewer != $toView and $viewer != ""){
            if(Clients::where("trainerId",$viewer)->where("userId",$toView)->count() > 0) return $defaultPermissions;
            if($toWhat!= ""){
                $permission = self::checkPermission($toView,$toWhat);
                if($permission == "private") $defaultPermissions["view"] = false;
                if($permission == "friends" and Friends::where("followingId",$viewer)->where("userId",$toView)->count() == 0) $defaultPermissions["view"] = false;
            } else {
                $defaultPermissions["view"] = true;
                $defaultPermissions["add"] = false;
                $defaultPermissions["edit"] = false;
                $defaultPermissions["delete"] = false;
                $defaultPermissions["self"] = false;
            }

        }
        return $defaultPermissions;
    }

    public static function checkPermission($userId,$request){
        $userPermission = Permissions::where("userId",$userId)->where("widget",$request)->first();

        if($userPermission){
            return $userPermission->access;
        } else{
            return true;
        }
    }

    public static function checkPermissionString($userId,$request){
        $userPermission = Permissions::where("userId",$userId)->where("widget",$request)->first();

        if($userPermission){
            return $userPermission->access;
        } else{
            return "";
        }
    }


    public static function dateToUnix($date){
        return strtotime($date);
    }

    public static function unixToDate($date){
        return date("Y-m-d H:i:s",$date);
    }

    public static function checkUserFolder($userId){
        $path = Config::get("constants.userPath")."/".$userId;
        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0775, true, true);
        }

        if(!File::isDirectory($path.Config::get("constants.picturesPath"))){
            File::makeDirectory($path.Config::get("constants.picturesPath"), 0775, true, true);
        }
        if(!File::isDirectory($path.Config::get("constants.profilePath"))){
            File::makeDirectory($path.Config::get("constants.profilePath"), 0775, true, true);
        }
        if(!File::isDirectory($path.Config::get("constants.exercisesPath"))){
            File::makeDirectory($path.Config::get("constants.exercisesPath"), 0775, true, true);
        }
        if(!File::isDirectory($path.Config::get("constants.videosExercisesPath"))){
            File::makeDirectory($path.Config::get("constants.videosExercisesPath"), 0775, true, true);
        }
        if(!File::isDirectory($path.Config::get("constants.exercisesCustomPath"))){
            File::makeDirectory($path.Config::get("constants.exercisesCustomPath"), 0775, true, true);
        }
    }

    public static function verifyFolder($destination){
        if (!File::isDirectory($destination)) {
            // Create the directory with proper permissions
            File::makeDirectory($destination, 0775, true, true);
        }
    }

    public static function userTypeFolder($folder){
        return strtolower($folder);

    }

    public static function uploadFile($file, $destination){

        $filename = Uuid::uuid4()->toString();
        //$filename = $file->getClientOriginalName();
        $extension =$file->getClientOriginalExtension();
        $filename .= ".".$extension;

        $upload_success = $file->move($destination, $filename);

        if( $upload_success ) {
           return $destination."/".$filename;
        } else {
           Session::flash('message',Lang::get("messagesVideoUploadFailed"));
           return "";
        }
    }
    public static function setNumber($currentSet, $totalSets){
        $coe = null;
        $currentSet =  $currentSet - 1;
        if($totalSets > 0){
            $coe = floor($currentSet / $totalSets);
        } else {
            $coe = 1;
        }
        if($coe > 0){
            return (($currentSet - ($totalSets * $coe))+1);
        } else {
            return ($currentSet+1);
        }

    }

    public static function saveImage($image, $destination,$url=""){


        static::verifyFolder($destination);


        $pathImg = "";
        $pathOriginal = "";
        $pathThumb = "";

        $extension = null;

        if($url != ""){
            $info = getimagesize($url);
            $extension = image_type_to_extension($info[2]);
        } else {
            $info = getimagesize($image);
            $extension = image_type_to_extension($info[2]);
        }

        $imagePHP = $image;
        $img = Image::make($image);
        $original = Image::make($image);
        $thumb = Image::make($image);

        $name = Uuid::uuid4()->toString();

        $pathImg = $destination."/".$name.$extension;
        $pathOriginal = $destination."/".$name."_original".$extension;
        $pathThumb = $destination."/".$name."_thumb".$extension;

        if ($extension == '.gif') {
            Log::error("ExtensionENTERED: ".$extension);
            try{
                // $glu = GluImage::get( $imagePHP );
                // if($thumb->width() > Config::get("constants.thumbSize") || $thumb->height() > Config::get("constants.thumbSize")){
                //     if($thumb->width() > $thumb->height()){
                //                                                 $glu->resize(Config::get("constants.thumbSize"));

                //     } else {
                //                                                 $glu->resize(null,Config::get("constants.thumbSize"));
                //     }
                // }

                // $glu->save( $pathThumb );
                // $glu = GluImage::get( $imagePHP );
                // if($img->width() > Config::get("constants.displaySize") || $img->height() > Config::get("constants.displaySize")){
                //    if($img->width() > $img->height()){
                //                                                 $glu->resize(Config::get("constants.displaySize"));
                //     } else {
                //                                                 $glu->resize(null,Config::get("constants.displaySize"));
                //     }
                // }
                //$glu->save( $pathImg );
                copy($imagePHP->getRealPath(), $pathThumb);
                copy($imagePHP->getRealPath(), $pathImg);
                copy($imagePHP->getRealPath(), $pathOriginal);
            } catch(\Exception $e){

                copy($imagePHP->getRealPath(), $pathThumb);
                copy($imagePHP->getRealPath(), $pathImg);
                copy($imagePHP->getRealPath(), $pathOriginal);

                Log::error($e);
            }


        } else {
            if($thumb->width() > Config::get("constants.thumbSize") || $thumb->height() > Config::get("constants.thumbSize")){
                $thumb->fit(Config::get("constants.thumbSize"),Config::get("constants.thumbSize"));
            }

            if($img->width() > Config::get("constants.displaySize") || $img->height() > Config::get("constants.displaySize")){
                $img->fit(Config::get("constants.displaySize"),Config::get("constants.displaySize"));
            }


            // if ($file->getClientOriginalExtension() == 'gif') {
            //     copy($file->getRealPath(), $pathImg);
            //     copy($file->getRealPath(), $pathOriginal);
            // }
            // else {
            //     $img->save($pathImg);
            //     $original->save($pathOriginal);
            // }

            $img->save($pathImg);
            $original->save($pathOriginal);
            $thumb->save($pathThumb);
        }


        return array("original"=>$pathOriginal,"image"=>$pathImg,"thumb"=>$pathThumb);

    }

    public static function saveImageGreenScreen($image, $destination,$light=130,$modulation=53.3,$feather=5,$algo = 2,$replacer=8,$color1="509F64,149545,078732,5EC590,3D8C40,3AAF6B,044006,39C473,46DB87,30A861,339E56,3BD97C,35D06E,4FE88D"){

        if($light == "") $light = 130;
        if($modulation == "") $modulation = 53.3;
        if($feather == "") $feather = 5;
        if($algo == "") $algo = 2;
        if($replacer == "") $replacer = 8;
        if($color1 == "") $color1 = "509F64,149545,078732,5EC590,3D8C40,3AAF6B,044006,39C473,46DB87,30A861,339E56,3BD97C,35D06E,4FE88D";
        $colors = explode(",",$color1);
        /*

        /usr/bin/convert /Users/diego/GitHub/TrainerWorkout/app/storage/temp/temp.jpg -modulate 130,100,53.3  -colorspace HSL -channel Hue,Saturation -separate +channel \( -clone 0 -background none -fuzz 45% -transparent grey64 \)  \( -clone 1 -background none -fuzz 50% -transparent black \) -delete 0,1 -alpha extract -compose multiply -composite /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-1.png
        /usr/bin/convert /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-1.png -morphology Smooth Square -negate /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-2.png
        /usr/bin/convert /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-2.png -background none -alpha background \( -clone 0 -alpha off -median 7x7 \) \( -clone 0 -alpha extract \) -delete 0 -alpha off -compose copy_opacity -composite /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-3.png
        /usr/bin/convert /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-3.png -morphology erode:2 disk:1 /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-4.png
        /usr/bin/composite -compose CopyOpacity /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-4.png /Users/diego/GitHub/TrainerWorkout/app/storage/temp/temp.jpg /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-5.png
        /usr/bin/convert -fuzz 8% -transparent \#509F64 /Users/diego/GitHub/TrainerWorkout/app/storage/temp/1AED4408-F099-D7D3-B855-24E2300645BD-5.png /Users/diego/GitHub/TrainerWorkout/app/storage/temp/final-6.png

        */


        static::verifyFolder($destination);


        $pathImg = "";
        $pathOriginal = "";
        $pathThumb = "";

        $img = Image::make($image);
        $original = Image::make($image);
        //$thumb = Image::make($image);


        $name = Uuid::uuid4()->toString();
        $name_temp = storage_path()."/temp/".$name;
        $pathImg = $destination."/".$name.".png";
        $pathOriginal = $destination."/".$name."_original.png";
        $pathThumb = $destination."/".$name."_thumb.png";



        //if($img->width() > Config::get("constants.displaySize") || $img->height() > Config::get("constants.displaySize")){
        //    $img->fit(Config::get("constants.displaySize"),Config::get("constants.displaySize"));
        //}
        // resize the image to a width of 300 and constrain aspect ratio (auto height)
        if($img->width() > $img->height()){
            $img->resize(null, Config::get("constants.displaySize"), function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize(Config::get("constants.displaySize"), null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }


        $img->save($pathImg);
        $original->save($pathOriginal);
        //$thumb->save($pathThumb);

        $convert = "/usr/local/bin/convert";
        $composite = "/usr/local/bin/composite";
        if(Config::get("app.debug")){
             $convert = "/usr/local/bin/convert";
             $composite = "/usr/local/bin/composite";
        }

        //REMOVE GREENSCREEN FROM THUMB AND IMAGE





        if($algo == 1){
            if(Config::get("app.debug")){
                 Log::error($convert." ".$pathImg." -modulate ".($light-40).",100,".$modulation." -colorspace HSL -channel Hue,Saturation -separate +channel \( -clone 0 -background none -fuzz 45% -transparent grey64 \)  \( -clone 1 -background none -fuzz 50% -transparent black \) -delete 0,1 -alpha extract -compose multiply -composite ".$name_temp.".png");
                 Log::error($convert." ".$name_temp.".png -morphology Smooth Square -negate ".$name_temp.".png");
                 Log::error($convert." ".$name_temp.".png -background none -alpha background \( -clone 0 -alpha off -median 15x15 \) \( -clone 0 -alpha extract \) -delete 0 -alpha off -compose copy_opacity -composite ".$name_temp.".png");
                 Log::error($convert." ".$name_temp.".png -morphology erode:5 disk:5 ".$name_temp.".png");
                //exec("convert ".$name_temp.".png -mask matte-negated.png -modulate 100,0,25 +mask ".$name_temp.".png");
                 Log::error($composite."  -compose CopyOpacity ".$name_temp.".png ".$pathImg." ".$name_temp.".png");
                 foreach($colors as $col){
                    Log::error($convert." -fuzz ".$replacer."% -transparent \#".$col." ".$name_temp.".png ".$name_temp.".png");
                    }
                 Log::error($convert." -fuzz ".$replacer."% -transparent \#006C1B ".$name_temp.".png ".$name_temp.".png");
                 Log::error($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -morphology Distance Euclidean:0,2\! +channel ".$pathImg);
            }
            exec($convert." ".$pathImg." -modulate ".($light-40).",100,".$modulation." -colorspace HSL -channel Hue,Saturation -separate +channel \( -clone 0 -background none -fuzz 45% -transparent grey64 \)  \( -clone 1 -background none -fuzz 50% -transparent black \) -delete 0,1 -alpha extract -compose multiply -composite ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -morphology Smooth Square -negate ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -background none -alpha background \( -clone 0 -alpha off -median 5x5 \) \( -clone 0 -alpha extract \) -delete 0 -alpha off -compose copy_opacity -composite ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -morphology erode:1 disk:1 ".$name_temp.".png");
            //exec("convert ".$name_temp.".png -mask matte-negated.png -modulate 100,0,25 +mask ".$name_temp.".png");
            exec($composite."  -compose CopyOpacity ".$name_temp.".png ".$pathImg." ".$name_temp.".png");
            foreach($colors as $col){
                exec($convert." -fuzz ".$replacer."% -transparent \#".$col." ".$name_temp.".png ".$name_temp.".png");
            }
            //exec($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -morphology Distance Euclidean:0,2\! +channel ".$pathImg);
            exec($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -blur 0x".$feather."  -level 50,100% +channel -background transparent -flatten -normalize ".$pathImg);
        } else {
            if(Config::get("app.debug")){
                Log::error("convert ".$pathImg." \( +clone -fx 'p{0,0}' \)  -compose Difference  -composite   -modulate 160,100,53.3   +matte  ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -colorspace RGB -contrast-stretch 5%,5%% ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -morphology Smooth Square -negate ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -background none -alpha background \( -clone 0 -alpha off -median 15x15 \) \( -clone 0 -alpha extract \) -delete 0 -alpha off -compose copy_opacity -composite ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -fill none -fuzz 15% -draw 'matte 0,0 floodfill' -flop  -draw 'matte 0,0 floodfill' -flop ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -alpha off -fill white -colorize 100 -alpha on ".$name_temp.".png");
                Log::error($convert." ".$name_temp.".png -morphology erode:5 disk:5 ".$name_temp.".png");
                Log::error($composite."  -compose CopyOpacity ".$name_temp.".png ".$pathImg." ".$name_temp.".png");
                foreach($colors as $col){
                    Log::error($convert." -fuzz ".$replacer."% -transparent \#".$col." ".$name_temp.".png ".$name_temp.".png");
                }
                Log::error($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -morphology Distance Euclidean:0,".$feather."\! +channel ".$pathImg);
            }
            exec("convert ".$pathImg." \( +clone -fx 'p{0,0}' \)  -compose Difference  -composite   -modulate 160,100,53.3   +matte  ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -colorspace RGB -contrast-stretch 5%,5% ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -morphology Smooth Square -negate ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -background none -alpha background \( -clone 0 -alpha off -median 15x15 \) \( -clone 0 -alpha extract \) -delete 0 -alpha off -compose copy_opacity -composite ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -fill none -fuzz 15% -draw 'matte 0,0 floodfill' -flop  -draw 'matte 0,0 floodfill' -flop ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -alpha off -fill white -colorize 100 -alpha on ".$name_temp.".png");
            exec($convert." ".$name_temp.".png -morphology erode:5 Octagon:10 ".$name_temp.".png");
            exec($composite."  -compose CopyOpacity ".$name_temp.".png ".$pathImg." ".$name_temp.".png");
            foreach($colors as $col){
                exec($convert." -fuzz ".$replacer."% -transparent \#".$col." ".$name_temp.".png ".$name_temp.".png");
            }
            //exec($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -morphology Distance Euclidean:0,".$feather."\! +channel ".$pathImg);
            exec($convert." ".$name_temp.".png -alpha set -virtual-pixel transparent -channel A -blur 0x".$feather."  -level 50,100% +channel -background transparent -flatten -normalize ".$pathImg);
        }


        $img = Image::make($pathImg);
        $thumb = Image::make($pathImg);

        if($thumb->width() > Config::get("constants.thumbSize") || $thumb->height() > Config::get("constants.thumbSize")){
            $thumb->fit(Config::get("constants.thumbSize"),Config::get("constants.thumbSize"));
        }

        $img->save($pathImg);
        //$original->save($pathOriginal);
        $thumb->save($pathThumb);

        return array("original"=>$pathOriginal,"image"=>$pathImg,"thumb"=>$pathThumb);

    }

    public static function userHome(){
        if (Auth::check())
        {
            return array('userName' => Auth::user()->firstName.Auth::user()->lastName);
        }

    }

    public static function getAge($birthDate){

        $datetime1 = new DateTime(date("Y-m-d H:i:s"));
        $datetime2 = new DateTime($birthDate);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%y');

    }

    public  static function changeDateFormatForPickerAv($d){

        $js = "var availableDates = [";

        for ($x = 0; $x < count($d); $x++){

            $tmp = explode("-", $d[$x]);

            dd($tmp);

            if ($tmp[1][0]=="0") $tmp[1]=$tmp[1][1];
            if ($tmp[2][0]=="0") $tmp[2]=$tmp[2][1];

            $d[$x] = "'".$tmp[2]."-".$tmp[1]."-".$tmp[0]."'";

            $js .= $d[$x];
            if ($x<count($d)-1) $js .= ",";
        }

        $js .= "];";

        return $js;
    }

    public static function getTypeOfAdd($userId){
        if($userId != Auth::user()->id){
            return "ajaxSaveExternal";
        }
        return "ajaxSave";
    }

    public static function getTypeOfCall($userId){

        if($userId === null or $userId == "") return "";
        if(is_numeric($userId)){

            if($userId != Auth::user()->id){
                return "External";
            }
            return "";
        } else {
            if($userId->id != Auth::user()->id){
                return "External";
            }
            return "";
        }

    }

    public static function formatPhone($phone){

            if($phone != ""){

                $rx = "/
            (1)?\D*     # optional country code
            (\d{3})?\D* # optional area code
            (\d{3})\D*  # first three
            (\d{4})     # last four
            (?:\D+|$)   # extension delimiter or EOL
            (\d*)       # optional extension
        /x";
        preg_match($rx, $phone, $matches);
        if(!isset($matches[0])) return false;

        $country = $matches[1];
        $area = $matches[2];
        $three = $matches[3];
        $four = $matches[4];
        $ext = $matches[5];

        $out = "$three-$four";
        if(!empty($area)) $out = "$area-$out";
        if(!empty($country)) $out = "+$country-$out";
        if(!empty($ext)) $out .= "x$ext";

        // check that no digits were truncated
        // if (preg_replace('/\D/', '', $s) != preg_replace('/\D/', '', $out)) return false;
        return $out;
    } else {
        return "";
    }
    }






    public static function userType($userType){
        switch(strtolower($userType)):
            case "":
                return "Trainee";
                break;
            case "trainee":
                return "Trainee";
                break;
            case "trainer":
                return "Trainer";
                break;
            default:
                return "Trainee";
        endswitch;

    }


    public static function APIOK(){
        $result = array();

        $result["data"] = "";
        $result["status"] = "ok";
        $result["message"] = "";
        $result["total"] = "";

        return $result;
    }

    public static function APIERROR(){
        $result = array();

        $result["data"] = "";
        $result["status"] = "error";
        $result["message"] = "";
        $result["total"] = "";

        return $result;
    }

    public static function pre(){
        echo "<pre>";
    }

    public static function printLastQuery($lines=1){
        $queries = DB::getQueryLog($lines);
        echo "<pre>";
        print_r(array_reverse($queries));
        echo "</pre>";

    }

    public static function prep(){
        echo "</pre>";
    }

    public static function now(){
        return date("Y-m-d H:i:s");
    }

    public static function nowDate(){
        return date("Y-m-d");
    }


    public static function startOfDay(){
        return date("Y-m-d")." 00:00:00";
    }

    public static function endOfDay(){
        return date("Y-m-d")." 23:59:59";
    }

    public static function Intercom(){
        try{
            $intercom = IntercomBasicAuthClient::factory(array(
            'app_id' => Config::get("constants.intercom_app_id"),
                'api_key' => Config::get("constants.intercom_api_key")
            ));

            return $intercom;
        } catch(Exception $e){
            //Log::error("Handle Error");
            //Log::error($e);
            return null;
        }
    }



}

?>
