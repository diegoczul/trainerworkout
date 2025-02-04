<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
  <meta charset="utf-8">
  
  <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!--  Mobile Viewport Fix
  j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
  device-width : Occupy full width of the screen in its current orientation
  initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
  maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
  -->
  <meta name="author" content="TrainerWorkout.com" />
  <meta name="robots" content="all"/>
  <meta name="distribution" content="global"/>
  <meta name="resource-type" content="document"/>
  <meta name="language" content="en-us"/>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="copyright" content="TrainerWorkout.com Copyright 2013. All Rights Reserved.">
  <meta name ="viewport" content="initial-scale=1.0, user-scalable = no, width = device-width">
  <link rel="shortcut icon" href="/img/favicon.ico">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="manifest" href="/manifest.json">
  <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">

  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/img/apple-touch-icon.png" />
  <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-touch-icon.png" />
  <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon.png" />
  <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png" />
  <!-- The is the icon for iOS's Web Clip.
     - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
     - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
     - Transparency is not recommended (iOS will put a black BG behind the icon) -->
    
  <meta name="google-site-verification" content="">
  
  <title>TrainerWorkout Web-App</title>
    
  <meta name="title" content="">
  <meta name="description" content="">


    {{ HTML::style('/css/Trainer/mobileInnerstyle.css') }}
    {{ HTML::style('/fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('/fw/datapicker/jquery.ui.timepicker.css'); }}
    {{ HTML::style('/css/lang/styles_'.Config::get('app.locale').'.css'); }}
    {{ HTML::script('/js/jquery-1.11.0.js'); }}
    {{ HTML::script('/fw/jquery-ui-1.11.1.custom/jquery-ui.min.js') }}
    {{ HTML::script('/js/modernizr_touch.js'); }} 

    {{ HTML::style('/fw/fancybox/source/jquery.fancybox.css?v=2.1.5');}}
    {{ HTML::style('/fw/lightbox/css/lightbox.css'); }}

    {{ HTML::style('/fw/chosen_v1/chosen.css') }}

    @if(Config::get("app.whitelabel") != "default")
      <?php $whitelabel = "ymca"; ?>
    @endif

    @yield("headerScripts")

    <!-- Open Sans Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,800' rel='stylesheet' type='text/css'>

</head>
<body class="trainer">


<div id="o-wrapper" class="o-wrapper">
<div class="systemMessages"></div>





    @yield("content")




<!-- SIDE MENU  Needs to stay below FOOTER and outsode of O-wrapper  -->
</div> <!-- End of O-wrapper -->
<!-- /c-menu push-left -->
<div id="c-mask" class="c-mask"></div><!-- /c-mask -->





<!-- End of Side Mneu -->

    

    
  

    <div class='loader-bg'>
      <img src='/img/tw-gif.gif'>
    </div>
</body>

   
    

</html>