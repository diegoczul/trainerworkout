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

@yield("header")

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="description" content="{{ Lang::get("content.frontEnd/description") }}" />
<meta name="keywords" content="{{ Lang::get("content.frontEnd/keywords") }}" />
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
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="manifest" href="/manifest.json">
<!-- The is the icon for iOS's Web Clip.
         - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
         - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
         - Transparency is not recommended (iOS will put a black BG behind the icon) -->
<title>{{ Lang::get("content.frontEnd/title") }}</title>


    <!-- NEw Home Page CSS -->
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <!-- Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,800' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">

    <!-- Website Css -->
    <!-- Core style -->
    {{ HTML::style('/css/website/style.css') }}

    <!-- French adjustments -->
    {{ HTML::style('/css/lang/styles_'.Config::get('app.locale').'.css'); }}



    {{ HTML::script('js/modernizr.js'); }}

    @if(Config::get("app.whitelabel") != "default")
      {{ HTML::style(Config::get("app.whitelabel_css")) }}
    @endif
    
</head>

<body>
    <div id="systemMessages" class="systemMessages"></div>
    <nav id="topNav" class="top">
        <div class="wrapper navRoot">
            <div class="nav_block logo">
                <a href="/">
                    <svg class="level_one" width="66" height="39" viewBox="0 0 66 39" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <title>trainer workout</title>
                        <path d="M6.9564073,17.1370515 C8.66819567,17.1370515 9.83009541,16.5600247 10.9843277,15.9536869 C5.82680764,16.9320954 0.736986662,13.330164 0,7.31975504 L8.54398367,7.19144354 L8.90185904,4.93059289 L14.6519362,4.40040084e-17 L19.584905,0 L18.4227312,6.97148571 C18.4227312,6.97148571 22.3325204,6.84805636 25.1869838,6.97148572 C26.9394177,7.19144354 28.9916796,7.83602304 29.8449008,8.85046057 C30.9128868,9.86870177 32.0705455,12.0164271 32.6295085,15.4897779 C32.8483231,16.5570219 33.2080204,18.5732349 33.3484298,19.9822423 L39.09242,7.43233453 C40.9487862,6.54162314 45.1337889,6.08022019 48.8690622,7.31975504 L50.6827305,21.9195987 L61.0702285,4.9305929 L64.7450431,3.67859491 L66,7.31975504 L52.6361763,36.829852 C49.3592042,37.8757153 45.9500323,37.5768934 43.9872869,36.829852 L41.5302243,22.1178643 C41.5302243,22.1178643 35.1251949,35.3977286 34.2483917,36.829852 C31.2794757,37.8757153 27.008634,37.4638618 25.6561992,36.829852 C25.6561992,35.5996679 25.6561992,22.3203952 24.9856844,20.2335432 C24.9856844,20.2335432 24.6257955,16.3341702 22.3325204,15.9536869 L16.9769052,15.9536869 C16.9769052,15.9536869 14.828511,27.6253059 15.0812226,27.999803 C15.2525323,28.5274974 15.5619871,28.595551 15.8704609,28.8554852 C17.1146595,29.1038286 17.3902654,29.3257248 22.0240465,28.1766244 L22.0240465,35.3576021 C19.3621833,37.0795684 15.9466962,38.6798878 10.3224433,37.8757153 C7.19423844,37.3064147 4.83250851,35.6559735 4.69622671,31.4785016 C4.94122022,28.3401738 5.85320029,24.3232787 6.9564073,17.1370515 Z" stroke="none" fill="#FFFFFF" fill-rule="evenodd"></path>
                    </svg>
                    <p>Trainer Workout</p>
                </a>
            </div>
            <div class="nav_block primary">
                <!-- <a href="/" class="nav_item selected">{{ Lang::get("content.frontEnd/PersonalTrainer") }}</a> -->
                <!-- <a href="/gym" class="nav_item">{{ Lang::get("content.frontEnd/Gym") }}</a> -->
                <!-- <a href="/blog" class="nav_item">{{ Lang::get("content.frontEnd/Blog") }}</a> -->
            </div>
            <div class="nav_block secondary">
                <a href="{{ Lang::get("routes./login") }}">{{ Lang::get("content.frontEnd/Login") }}</a>
            </div>
            <div class="mobile">            
                <svg class="level_one" width="40" height="31" viewBox="0 0 40 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" onclick="openMobileMenu()">
                    <title>open menu</title>
                    <g id="Group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="0" width="40" height="5" rx="2"></rect>
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="13" width="40" height="5" rx="2"></rect>
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="26" width="40" height="5" rx="2"></rect>
                    </g>
                </svg>
                <div class="mobileMenu" id="mobileMenu">
                    <div class="mobileMenuContent">
                        <svg class="close--mobileMenu" width="25px" height="25px" viewBox="706 29 25 25" version="1.1" xmlns="http://www.w3.org/2000/svg">
                            <title>close menu</title>
                            <g id="Group-19" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" transform="translate(706.000000, 29.000000)">
                                <path d="M10.9375,-0.78125 L11.6364883,-1.82973249 C12.1133927,-2.54508898 12.886236,-2.54564593 13.3635117,-1.82973249 L14.0625,-0.78125 L14.0625,25 L13.3635117,26.0484825 C12.8866073,26.763839 12.113764,26.7643959 11.6364883,26.0484825 L10.9375,25 L10.9375,-0.78125 Z" id="Rectangle-17" fill="#04A2E4" transform="translate(12.500000, 12.109375) rotate(45.000000) translate(-12.500000, -12.109375) "></path>
                                <path d="M10.9375,-0.78125 L11.6364883,-1.82973249 C12.1133927,-2.54508898 12.886236,-2.54564593 13.3635117,-1.82973249 L14.0625,-0.78125 L14.0625,25 L13.3635117,26.0484825 C12.8866073,26.763839 12.113764,26.7643959 11.6364883,26.0484825 L10.9375,25 L10.9375,-0.78125 Z" id="Rectangle-17" fill="#04A2E4" transform="translate(12.500000, 12.109375) scale(-1, 1) rotate(45.000000) translate(-12.500000, -12.109375) "></path>
                            </g>
                        </svg>
                        <!-- <a href="/" class="nav_item selected">{{ Lang::get("content.frontEnd/PersonalTrainer") }}</a> -->
                        <!-- <a href="/gym" class="nav_item">{{ Lang::get("content.frontEnd/Gym") }}</a> -->
                        <!-- <a href="/blog" class="nav_item">{{ Lang::get("content.frontEnd/Blog") }}</a> -->
                        <a href="/lang/en" class="nav_item">English</a>
                        <a href="/lang/fr" class="nav_item">Français</a>
                    </div>
                    <div class="mobileMenuLogin">
                        <a href="{{ Lang::get("routes./login") }}" class="nav_item login">{{ Lang::get("content.frontEnd/Login") }}</a>
                        <!-- <a href="{{ Lang::get("routes./TrainerSignUp") }}" class="nav_item signup">{{ Lang::get("content.Get started for free") }}</a> -->
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <nav id="scrollNav" class="hide">
        <div class="wrapper navRoot">
            <div class="nav_block logo">
                <a href="/">
                    <svg class="level_one" width="66" height="39" viewBox="0 0 66 39" version="1.1" xmlns="http://www.w3.org/2000/svg">
                        <title>trainer workout</title>
                        <path d="M6.9564073,17.1370515 C8.66819567,17.1370515 9.83009541,16.5600247 10.9843277,15.9536869 C5.82680764,16.9320954 0.736986662,13.330164 0,7.31975504 L8.54398367,7.19144354 L8.90185904,4.93059289 L14.6519362,4.40040084e-17 L19.584905,0 L18.4227312,6.97148571 C18.4227312,6.97148571 22.3325204,6.84805636 25.1869838,6.97148572 C26.9394177,7.19144354 28.9916796,7.83602304 29.8449008,8.85046057 C30.9128868,9.86870177 32.0705455,12.0164271 32.6295085,15.4897779 C32.8483231,16.5570219 33.2080204,18.5732349 33.3484298,19.9822423 L39.09242,7.43233453 C40.9487862,6.54162314 45.1337889,6.08022019 48.8690622,7.31975504 L50.6827305,21.9195987 L61.0702285,4.9305929 L64.7450431,3.67859491 L66,7.31975504 L52.6361763,36.829852 C49.3592042,37.8757153 45.9500323,37.5768934 43.9872869,36.829852 L41.5302243,22.1178643 C41.5302243,22.1178643 35.1251949,35.3977286 34.2483917,36.829852 C31.2794757,37.8757153 27.008634,37.4638618 25.6561992,36.829852 C25.6561992,35.5996679 25.6561992,22.3203952 24.9856844,20.2335432 C24.9856844,20.2335432 24.6257955,16.3341702 22.3325204,15.9536869 L16.9769052,15.9536869 C16.9769052,15.9536869 14.828511,27.6253059 15.0812226,27.999803 C15.2525323,28.5274974 15.5619871,28.595551 15.8704609,28.8554852 C17.1146595,29.1038286 17.3902654,29.3257248 22.0240465,28.1766244 L22.0240465,35.3576021 C19.3621833,37.0795684 15.9466962,38.6798878 10.3224433,37.8757153 C7.19423844,37.3064147 4.83250851,35.6559735 4.69622671,31.4785016 C4.94122022,28.3401738 5.85320029,24.3232787 6.9564073,17.1370515 Z" stroke="none" fill="#FFFFFF" fill-rule="evenodd"></path>
                    </p>
                </a>
            </div>
            <div class="nav_block primary">
                <!-- <a href="/" class="nav_item selected">{{ Lang::get("content.frontEnd/PersonalTrainer") }}</a> -->
                <!-- <a href="/gym" class="nav_item">{{ Lang::get("content.frontEnd/Gym") }}</a> -->
                <!-- <a href="/blog" class="nav_item">{{ Lang::get("content.frontEnd/Blog") }}</a> -->
                <a href="{{ Lang::get("routes./login") }}">{{ Lang::get("content.frontEnd/Login") }}</a>
            </div>
            <div class="nav_block secondary">
                <!-- <a href="{{ Lang::get("routes./TrainerSignUp") }}" class="action">{{ Lang::get("content.Get started for free") }}</a> -->
            </div>
            <div class="mobile">            
                <svg class="level_one" width="40" height="31" viewBox="0 0 40 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" onclick="openMobileMenu()">
                    <title>open menu</title>
                    <g id="Group" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="0" width="40" height="5" rx="2"></rect>
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="13" width="40" height="5" rx="2"></rect>
                        <rect id="Rectangle-8" fill="#FFFFFF" x="0" y="26" width="40" height="5" rx="2"></rect>
                    </g>
                </svg>
            </div>
        </div>
    </nav>

    @yield("content")

            <footer>
                <div class="wrapper footerContainer">
                <div class="footer_block language">
                    <h2>{{ Lang::get("content.frontEnd/Language") }}</h2>
                    <div>
                        <a href="/lang/en" {{ App::getLocale() == "en" ? 'class="selected"' : "" }}>English</a>
                    </div>
                    <div>
                        <a href="/lang/fr" {{ App::getLocale() == "fr" ? 'class="selected"' : "" }}>Français</a>
                    </div>
                    
                </div>
                <div class="breakLine"></div>
            <!--     <div class="footer_block pages">
                    <div class="page personalTrainers"> -->
                        <!-- <h2><a href="/#jumbotron">{{ Lang::get("content.frontEnd/PersonalTrainer") }}</a></h2>
                        <a href="/#primary">{{ Lang::get("content.frontEnd/Mobilefriendly") }}</a>
                        <a href="/#secondary series of tools designed for you">{{ Lang::get("content.frontEnd/Builtfyou_clients") }}</a>
                        <a href="/#third">{{ Lang::get("content.frontEnd/Toolssavetime") }}</a>
                        <a href="/#pricing">{{ Lang::get("content.frontEnd/PricingPlans") }}</a> -->
                  <!--   </div> -->
                    <!-- <div class="page gyms">
                        <h2><a href="">{{ Lang::get("content.frontEnd/Gym") }}</a></h2>
                        <a href="">Link to come with page</a>
                        <a href="">Link to come with page</a>
                        <a href="">Link to come with page</a>
                        <a href="">Link to come with page</a>
                    </div> -->
                <!-- </div> -->
                <div class="breakLine"></div>
                <div class="footer_block other">
                    <!-- <h2><a href="/Blog">{{ Lang::get("content.frontEnd/Blog") }}</a></h2> -->
                    <h2><a href="mailto:info@trainerworkout.com">{{ Lang::get("content.frontEnd/ContactUs") }}</a></h2>
                </div>
                <div class="breakLine desktopHide"></div>
                <div class="footer_block newsletter">
                    <p>{{ Lang::get("content.frontEnd/Keepupdatedreleases") }}</p>
                    <form class="newsletter" method="POST" action="{{ Lang::get("routes./registerNewsletter") }}" accept-charset="UTF-8" class="">
                        <input name="_token" type="hidden" value="WP96IYz8k3m3x5rWBF5prcxnvGzhFrZRx1jPIJ67">
                        <input class="newsletter" type="text" placeholder="{{ Lang::get("content.newsletter") }}" name="email" />
                        <button class="newsletter click_1 ajaxSaveNewsletter" type="submit">
                           {{ Lang::get("content.frontEnd/Subscribe") }}
                        </button>
                    </form>
                </div>
                <div class="breakLine desktopHide socialBreakLine"></div>
                <div class="footer_block social">
                    <div class="socialBottom">
                        <a href="mailto:info@trainerworkout.com"><img src="/img/socials/social_email.png" alt="email"></a>
                        <a target="_blank" href="https://www.instagram.com/trainerworkout"><img src="/img/socials/social_instagram.png" alt="instagram"></a>
                        <a target="_blank" href="https://twitter.com/trainerworkout"><img src="/img/socials/social_twitter.png" alt="twitter"></a>
                        <a target="_blank" href="https://www.facebook.com/tworkout/"><img src="/img/socials/social_facebook.png" alt="facebook"></a>           
                    </div>
                </div>
                                   <!-- <li><a href="{{ Lang::get("routes./TermsAndConditions") }}" class="page-scroll label_opacity">{{ Lang::get("content.frontEnd/terms") }}</a></li> -->
            </div>
        </footer>
</body>
</html>

<script type="text/javascript">


function openMobileMenu() {
    $("#mobileMenu").show();
}

$(".close--mobileMenu").click(function () {
    $("#mobileMenu").hide();
});

    function isScrolledIntoView(elem) {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();
        var elemTop = $(elem).offset().top + 80;
        var elemBottom = elemTop + $(elem).height();
        return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom) && (elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }

    $(window).scroll(function() {    
        if(isScrolledIntoView($('#topNav'))) {
            $("#scrollNav").attr("class", "hide");
        } else {
            $("#scrollNav").attr("class", "show");
        }
    });


</script>


    @yield('scripts')

    @if($errors->has())
       {{$message = "";}}
       @foreach ($errors->all() as $error)
        {{$message .= $error."</br>"; }}
      @endforeach
      <script>errorMessage("{!! $message !!}")</script>
    @endif

    @if(Session::has("message"))
      <script>successMessage("{!! Session::get("message") !!}")</script>
    @endif

    @if(Session::has("error"))
      <script>errorMessage("{!! Session::get("error") !!}")</script>
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
