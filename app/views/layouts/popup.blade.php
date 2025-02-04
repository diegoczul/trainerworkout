<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
<meta charset="utf-8">

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="description" content="Create your own workouts and diets and share them with your friends. Upload your own exercises. Keep track of all your measurements and progress. Buy workouts and diets online from professional trainers." />
<meta name="keywords" content="Create workout online,Create diet online,Buy workouts online,Buy diets online,Share workouts,Track your measurements,Track your progress,Find Personal Trainers,Find Gyms,Training Programs,Training Routines,Training,Create workout,Gym Management Software,Personal Training management Software" />
<meta name="google-site-verification" content="" />
<!-- don't forget to set the site up: https://google.com/webmasters -->

<meta name="author" content="TrainerWorkout.com" />
<meta name="robots" content="all"/>
<meta name="distribution" content="global"/>
<meta name="resource-type" content="document"/>
<meta name="language" content="en-us"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="copyright" content="TrainerWorkout.com Copyright 2013. All Rights Reserved.">

<!--  Mobile Viewport Fix
    j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
    device-width : Occupy full width of the screen in its current orientation
    initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
    maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
    -->
<meta name="viewport" content="width=1024" />
<link rel="shortcut icon" href="/img/favicon.ico">
  <!-- This is the traditional favicon.
     - size: 16x16 or 32x32
     - transparency is OK
     - see wikipedia for info on browser support: https:/mky.be/favicon/ -->
     
  <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
<!-- The is the icon for iOS's Web Clip.
         - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
         - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
         - Transparency is not recommended (iOS will put a black BG behind the icon) -->
<title>Trainer Workout | Create, buy and share workouts online Personal Training Software for Personal Trainers and Gym Management Software</title>
    {{ HTML::style('css/styles.css') }}
    {{ HTML::style('css/programmers_fixes.css') }}
    {{ HTML::style('fw/font-awesome-4.1.0/css/font-awesome.min.css'); }}
    {{ HTML::style('fw/datapicker/jquery.ui.timepicker.css'); }}

    {{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}
    {{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
    {{ HTML::style('css/lang/styles_'.Config::get('app.locale').'.css'); }}
    @yield("headerExtra")

    {{ HTML::style('css/Trainer/mobileInnerstyle.css') }}
</head>
<style type="text/css">
    .sharewokoutform {
            padding: 7px;
    }
    .sharewokoutform .friendholder {
            display: none;        
    }
    .sharewokoutform .friendholder ul {
            list-style: none;
            background-color: #F6F6F6;
            margin-top: 4px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            padding: 1px;
    }
    .sharewokoutform .friendholder ul li {
            padding-left: 7px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            border: 1px solid #FFF;
    }
    .sharewokoutform .friendholder ul li:hover {
            border: 1px solid #CCC;
            cursor: pointer;
            font-weight: bold;
            background-color: #D6D6D6;
    }
</style>
<body>

@yield("content")

</body>
<!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    {{ HTML::script('js/jquery-1.11.0.js'); }}
    {{ HTML::script('js/jquery-ui.js'); }}

    <!-- Bootstrap Core JavaScript -->
    {{ HTML::script('js/bootstrap.min.js'); }}

    {{ HTML::script('js/global.js'); }}
    {{ HTML::script('js/thirdParty.js'); }}

    <!-- CHOSEN SELCT BOX -->
    {{ HTML::script('fw/ckeditor/ckeditor.js'); }}

    <!-- CHOSEN SELCT BOX -->
    {{ HTML::script('fw/chosen_v1/chosen.jquery.js'); }}
    {{ HTML::script('fw/chosen_v1/docsupport/prism.js'); }}
    {{ HTML::script('fw/datapicker/jquery.ui.timepicker.js'); }}
    <script type="text/javascript">
        var config = {
          '.chosen-select'           : {},
          '.chosen-select-deselect'  : {allow_single_deselect:true},
          '.chosen-select-no-single' : {disable_search_threshold:10},
          '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
          '.chosen-select-width'     : {width:"95%"}
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
    </script>


    <!-- DataTables JavaScript -->
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->


    @yield('scripts')

    @if($errors->has())
       {{$message = "";}}
       @foreach ($errors->all() as $error)
        {{$message .= $error."</br>"; }}
      @endforeach
      <script>errorMessage("{{ $message }}")</script>
    @endif

    @if(Session::has("message"))
      <script>successMessage("{{ Session::get("message") }}")</script>
    @endif

    @if(Session::has("error"))
      <script>errorMessage("{{ Session::get("error") }}")</script>
    @endif

    @if(!Config::get("app.debug"))
          <script>
        //     window.intercomSettings = {
        //   // TODO: The current logged in user's full name
        //   name: "{{{ Auth::user()->getFullName }}}",
        //   // TODO: The current logged in user's email address.
        //   email: "{{{ Auth::user()->email }}}",
        //   // TODO: The current logged in user's sign-up date as a Unix timestamp.
        //   created_at: {{ Helper::dateToUnix(Auth::user()->created_at) }},
        //   app_id: "af0obxyk"
        // };
          </script>
          {{ HTML::script('js/thirdParty.js'); }}
    @endif



</body>

</html>

