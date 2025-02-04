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
<meta name="viewport" content="width=1024" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="shortcut icon" href="/img/favicon.ico">
<link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
<meta name="apple-mobile-web-app-capable" content="yes">
<link rel="manifest" href="/manifest.json">

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/img/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/img/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/img/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/img/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png" />
<!-- The is the icon for iOS's Web Clip.
         - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
         - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
         - Transparency is not recommended (iOS will put a black BG behind the icon) -->
<title>{{ Lang::get("content.frontEnd/title") }}</title>
  
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
                    <svg class="level_one" width="29px" height="31px" viewBox="0 0 29 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>ymcaLogoWhite</title>
                        <g id="YMCA" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Workouts-Large" transform="translate(-1000.000000, -21.000000)" fill="#FFFFFF">
                                <g id="ymcaLogoWhite" transform="translate(1000.000000, 21.000000)">
                                    <path d="M0.0878842711,0.683074245 C-0.13230144,0.305822757 0.0516157254,0 0.484503233,0 L7.85570787,0 C8.29494019,0 8.82578239,0.302440668 9.0519563,0.693827046 L18.4190943,16.9033459 C18.58553,17.1913572 18.7204528,17.6838966 18.7204528,18.0256605 L18.7204528,30.1914657 C18.7204528,30.6380069 18.3621119,31 17.9184598,31 L10.6613346,31 C10.2184062,31 9.85934166,30.6368972 9.85934166,30.1914657 L9.85934166,18.0256605 C9.85934166,17.6938346 9.72326393,17.1916901 9.55496961,16.9033459 L0.0878842711,0.683074245 Z" id="Rectangle-5"></path>
                                    <path d="M19.4015671,15.0387994 C19.6787655,15.5173151 20.1312268,15.5120636 20.4053733,15.0387994 L28.715045,0.693655068 C28.9369595,0.310559952 28.7652056,0 28.3250679,0 L11.6804934,-1.77635684e-15 C11.1333338,0 10.9175291,0.39316514 11.1916853,0.866429399 L19.4015671,15.0387994 Z M19.7855359,8.77975212 C19.8940359,8.96636634 20.0702117,8.96590259 20.1784334,8.77975212 L23.0837628,3.78233938 C23.1922541,3.59572516 23.1037583,3.44444444 22.8873062,3.44444444 L17.0764146,3.44444444 C16.8594233,3.44444444 16.7717434,3.59618891 16.8799737,3.78233938 L19.7855359,8.77975212 Z" id="Combined-Shape" fill-opacity="0.8"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                    <p>YMCA Quebec App</p>
                </a>
            </div>
            <div class="nav_block primary">
                <!-- <a href="/gym" class="nav_item">{{ Lang::get("content.frontEnd/Gym") }}</a> -->
            </div>
            <div class="nav_block secondary">
                <a href="{{ Lang::get("routes./TrainerSignUp") }}">{{ Lang::get("content.Register") }}</a>
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
                        <a href="/" class="nav_item selected">{{ Lang::get("content.frontEnd/PersonalTrainer") }}</a>
                        <!-- <a href="/gym" class="nav_item">{{ Lang::get("content.frontEnd/Gym") }}</a> -->
                        <a href="/blog" class="nav_item">{{ Lang::get("content.frontEnd/Blog") }}</a>
                        <a href="/lang/en" class="nav_item">English</a>
                        <a href="/lang/fr" class="nav_item">Français</a>
                    </div>
                    <div class="mobileMenuLogin">
                        <a href="{{ Lang::get("routes./login") }}" class="nav_item login">{{ Lang::get("content.frontEnd/Login") }}</a>
                        <a href="" class="nav_item signup">{{ Lang::get("content.Get started for free") }}</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <nav id="scrollNav" class="hide">
        <div class="wrapper navRoot">
            <div class="nav_block logo">
                <a href="/">
                    <svg class="level_one" width="29px" height="31px" viewBox="0 0 29 31" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <title>ymcaLogoWhite</title>
                        <g id="YMCA" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="Workouts-Large" transform="translate(-1000.000000, -21.000000)" fill="#FFFFFF">
                                <g id="ymcaLogoWhite" transform="translate(1000.000000, 21.000000)">
                                    <path d="M0.0878842711,0.683074245 C-0.13230144,0.305822757 0.0516157254,0 0.484503233,0 L7.85570787,0 C8.29494019,0 8.82578239,0.302440668 9.0519563,0.693827046 L18.4190943,16.9033459 C18.58553,17.1913572 18.7204528,17.6838966 18.7204528,18.0256605 L18.7204528,30.1914657 C18.7204528,30.6380069 18.3621119,31 17.9184598,31 L10.6613346,31 C10.2184062,31 9.85934166,30.6368972 9.85934166,30.1914657 L9.85934166,18.0256605 C9.85934166,17.6938346 9.72326393,17.1916901 9.55496961,16.9033459 L0.0878842711,0.683074245 Z" id="Rectangle-5"></path>
                                    <path d="M19.4015671,15.0387994 C19.6787655,15.5173151 20.1312268,15.5120636 20.4053733,15.0387994 L28.715045,0.693655068 C28.9369595,0.310559952 28.7652056,0 28.3250679,0 L11.6804934,-1.77635684e-15 C11.1333338,0 10.9175291,0.39316514 11.1916853,0.866429399 L19.4015671,15.0387994 Z M19.7855359,8.77975212 C19.8940359,8.96636634 20.0702117,8.96590259 20.1784334,8.77975212 L23.0837628,3.78233938 C23.1922541,3.59572516 23.1037583,3.44444444 22.8873062,3.44444444 L17.0764146,3.44444444 C16.8594233,3.44444444 16.7717434,3.59618891 16.8799737,3.78233938 L19.7855359,8.77975212 Z" id="Combined-Shape" fill-opacity="0.8"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                    <p>YMCA Quebec App</p>
                </a>
            </div>
            <div class="nav_block primary">
                <a href="{{ Lang::get("routes./login") }}">{{ Lang::get("content.frontEnd/Login") }}</a>
            </div>
            <div class="nav_block secondary">
                <a href="" class="action">Get started for free</a>
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
      <script>errorMessage("{{ $message }}")</script>
    @endif

    @if(Session::has("message"))
      <script>successMessage("{{ Session::get("message") }}")</script>
    @endif

    @if(Session::has("error"))
      <script>errorMessage("{{ Session::get("error") }}")</script>
    @endif

    @if(!Config::get("app.debug"))

          
          {{ HTML::script('js/thirdParty.js'); }}
    @endif

      @if(Auth::check())
          <script>
              Tawk_API.visitor = {
                  name  : '{{ Auth::user()->getCompleteName() }}',
                  email : '{{ Auth::user()->email }}'
              };
          </script>
          @endif

    @if(Request::get("utm") != "")

        <?php Session::put("utm",Request::get("utm")); ?>
        
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
