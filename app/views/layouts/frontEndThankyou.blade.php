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
  <meta name = "viewport" content="initial-scale=1.0, user-scalable = no, width = device-width">

<link rel="shortcut icon" href="/img/favicon.ico">
<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
<!-- The is the icon for iOS's Web Clip.
         - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
         - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
         - Transparency is not recommended (iOS will put a black BG behind the icon) -->
<title>{{ Lang::get("content.frontEnd/title") }}</title>
    <!-- NEw Home Page CSS -->
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('fw/font-awesome-4.1.0/css/font-awesome.min.css'); }}
    {{ HTML::style('css/homepage.css') }}
    {{ HTML::style('css/timeline.css') }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}

    {{ HTML::style('css/OpenSans.css'); }}
    {{ HTML::style('css/font-awesome.css'); }}
    {{ HTML::style('css/lang/styles_'.Config::get('app.locale').'.css'); }}

    {{ HTML::script('js/modernizr.js'); }}
    <!-- jQuery Version 1.11.0 -->
    {{ HTML::script('js/jquery-1.11.0.js'); }}
    {{ HTML::script('fw/jquery-ui-1.11.1.custom/jquery-ui.min.js'); }}
    
    
    
    <!-- Facebook Conversion Code for Thank you Page -->
<script>(function() {
    var _fbq = window._fbq || (window._fbq = []);
    if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
    }
    })();
    window._fbq = window._fbq || [];
    window._fbq.push(['track', '6038068455680', {'value':'0.01','currency':'CAD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6038068455680&amp;cd[value]=0.01&amp;cd[currency]=CAD&amp;noscript=1" /></noscript>
    
</head>

<body id="page-top" class="index">


    <div id="systemMessages" class="systemMessages"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="/"><img src="/img/logos/icon_logo.png" width="150px" height=""></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#witf">Who is this for</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#testimonials">Testimonials</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/#pricing">Price</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="https://workout.trainerworkout.com">Workout Builder</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/blog">Blog</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/login">Login</a>
                    </li>
                    <li id="get_started">
                        <a class="page-scroll get_started_header" href="/payment">Get early access</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    @yield("content")

    <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 footer_col">
                        <div class="footer_title">{{ Lang::get("content.fronEnd/TrainerWorkoutforcoaches") }}</div>
                        <ul>
                            <li><a href="/#page-top" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/CreateshareWorkouts") }}</a></li>
                            <li><a href="/#fast-workout" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Createworkoutsfast") }}</a></li>
                            <li><a href="/#two_block" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/TrainerWorkoutiseasy") }}</a></li>
                            <li><a href="/#customizable-workout" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Fullcustomization") }}</a></li>
                            <li><a href="/#client-view" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Yourclientsview") }}</a></li>
                            <li><a href="/#affordable" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Mostaffordable") }}</a></li>
                            <li><a href="/WorkoutBuilderPrice" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Pricing") }}</a></li>
                        </ul>
                    </div>
                <div class="col-md-4 footer_col">
                        <div class="footer_title label_opacity"><a href="/blog">{{ Lang::get("content.fronEnd/TheTrainerWorkoutBlog") }}</a></div>
    
                        <ul class="col-md-10">
                            <li><a class="label_opacity" href="https://trainerworkout.com/blog/personality-traits-personal-trainer/">{{ Lang::get("content.fronEnd/ImportantpersonalitytraitsforPTs") }}</a></li>
                            <li> <a class="label_opacity" href="https://trainerworkout.com/blog/wearable-technology/">{{ Lang::get("content.fronEnd/5newwearabletechtokeepaneyeon") }}</a></li>
                            <li> <a class="label_opacity" href="https://trainerworkout.com/blog/motivating-your-clients/">{{ Lang::get("content.fronEnd/Bestwaystokeepyourclientsmotivated") }}</a></li>
                        </ul>
    
                    </div> 
                    <div class="col-md-4 footer_col">
                        <div class="footer_title">Accounts</div>
                        <ul>
                            <li><a href="/TraineeInfo" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/SignUp") }}</a></li>
                            <li><a href="/login" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Login") }}</a></li>
                            <li><a href="/password/reset" class="page-scroll label_opacity">{{ Lang::get("content.fronEnd/Forgetpassword") }}</a></li>
                        </ul>
                        <div class="resp-appear">
                            <div class="footer_title">Contact Us") }}</div>
                            <ul class="socials">
                                <li><a href=""><img src="/img/socials/social_email.png" alt="email"></a></li>
                                <li><a href=""><img src="/img/socials/social_instagram.png" alt="email"></a></li>
                                <li><a href=""><img src="/img/socials/social_twitter.png" alt="email"></a></li>
                                <li><a href=""><img src="/img/socials/social_facebook.png" alt="email"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                   
                   
                   <form method="POST" action="https://trainerworkout.com/registerNewsletter" accept-charset="UTF-8" class=""><input name="_token" type="hidden" value="WP96IYz8k3m3x5rWBF5prcxnvGzhFrZRx1jPIJ67">
                    <div class="col-xs-4 no_padding resp-100"><input class="newsletter col-xs-12" type="text" placeholder="Newsletter Sign Up" name="email" /></div>
                    <div class="col-xs-4 no_padding resp-100">
                    <button class="newsletter_btn click_1 col-xs-7  resp-100 ajaxSaveNewsletter" type="submit">
                          <span class="newsletter_btn_text" class="label_opacity">{{ Lang::get("content.fronEnd/Subscribe") }}</span>
                    
                    </button>
                    </div>
                    </form>
                    
                    
                    
                    <div class="col-xs-4 no_padding resp-disapear">
                        <div class="footer_title offset">{{ Lang::get("content.fronEnd/Contact Us") }}</div>
                        <ul class="socials">
                            <li><a href="mailto:info@trainerworkout.com"><img src="/img/socials/social_email.png" alt="email" height="494" width="494"></a></li>
                            <li><a href="https://instagram.com/TrainerWorkout/"><img src="/img/socials/social_instagram.png" alt="email" height="494" width="494"></a></li>
                            <li><a href="https://twitter.com/TrainerWorkout"><img src="/img/socials/social_twitter.png" alt="email" height="512" width="512"></a></li>
                            <li><a href="https://www.facebook.com/tworkout"><img src="/img/socials/social_facebook.png" alt="email" height="530" width="512"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
</body>


    <!-- Bootstrap Core JavaScript -->
    {{ HTML::script('js/bootstrap.min.js'); }}  

    {{ HTML::script('fw/datapicker/jquery.ui.timepicker.js'); }}


    <!-- Home Page Javascript -->
    {{ HTML::script('js/jquery.easing.min.js'); }}
    {{ HTML::script('js/classie.js'); }}
    {{ HTML::script('js/cbpAnimatedHeader.js'); }}
    {{ HTML::script('js/agency.js'); }}
    {{ HTML::script('js/timeline.js'); }}
    {{ HTML::script('js/global.js'); }}
    



    <!-- CHOSEN SELCT BOX -->
    {{ HTML::script('fw/chosen_v1/chosen.jquery.js'); }}
    {{ HTML::script('fw/chosen_v1/docsupport/prism.js'); }}
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
          
        //   app_id: "af0obxyk"
        // };
          </script>
          
          {{ HTML::script('js/thirdParty.js'); }}
    @endif
<script>
          //ALL AJAX FORMS SAVE
    $("body").on("click",".ajaxSaveNewsletter",function(event){
        //alert(1);
        
        var handler = $(this);
        tForm = $(this).closest("form");
        widget = $(this).attr("widget");
        tForm.submit(function(e)
            {
                e.preventDefault(); //STOP default action
                e.stopImmediatePropagation();
                //var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");
                var preload;
                $.ajax(
                {
                    url : formURL,
                    type: "POST",
                    data: new FormData( this ),
                    processData: false,
                    contentType: false,
                    beforeSend:function() 
                    {
                                //preLoad = showLoadWithElement(handler);
                    },
                    success:function(data, textStatus, jqXHR) 
                    {
                        //hideLoadWithElement(preLoad);
                        successMessage(data);
                        //upAndClearAdd();
                        
                        //widgetsToReload.push(widget);
                        //refreshWidgets();
                        return false;

                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                        //hideLoadWithElement(preLoad);
                        errorMessage(jqXHR.responseText);
                        return false;
                    },
                    statusCode: {
                        500: function() {
                            if(jqXHR.responseText != ""){
                                errorMessage(jqXHR.responseText);
                            }
                            
                        }
                    }
                });
                
            }
        );
    });

 

          </script>

</body>

</html>
