@php
    use App\Http\Libraries\Helper;
@endphp
        <!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8">

    @yield("header")

    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="google-site-verification" content=""/>
    <!-- don't forget to set the site up: https://google.com/webmasters -->

    <meta name="author" content="TrainerWorkout.com"/>
    <meta name="robots" content="all"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta name="language" content="en-us"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="copyright" content="TrainerWorkout.com Copyright 2013. All Rights Reserved.">
    <meta name="viewport" content="initial-scale=1.0, user-scalable = no,width = device-width">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}">
    <link rel="apple-touch-icon" href="{{asset('assets/img/apple-touch-icon.png')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/fw/addtohomescreen/style/addtohomescreen.css')}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{asset('manifest.json')}}">

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <!-- The is the icon for iOS's Web Clip.
             - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
             - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
             - Transparency is not recommended (iOS will put a black BG behind the icon) -->
    <!-- Reset Settings of CSS -->
    {{ HTML::style(asset('assets/css/fw/normalize.css')) }}


    {{ HTML::style(asset('assets/css/Trainer/mobileInnerstyle.css')) }}
    {{ HTML::style(asset('assets/css/lang/styles_'.Config::get('app.locale').'.css')) }}


    {{ HTML::script(asset('assets/js/modernizr.js')) }}
    <!-- jQuery Version 1.11.0 -->

    {{ HTML::script(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.js')) }}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- AJAX SETUP -->
    <script type="text/javascript">
        $.ajaxSetup({
            header: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    @yield("headerExtra")

    {{ HTML::style(asset('assets/css/fw/OpenSans.css')) }}

    @if(Config::get("app.whitelabel") != "default")
        {{ HTML::style(Config::get("app.whitelabel_css")) }}
    @endif
</head>
<body>

<div id="o-wrapper" class="o-wrapper">
    <div class="systemMessages"></div>
    <div class="main_header">
        <div class="navContentContainer">
            <nav>
                <div class="navItem navBurger" id="c-button--push-left">
                    <svg width="39" height="35" viewBox="0 0 39 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            Mobile Menu Icon
                        </title>
                        <g transform="translate(2)" fill="#FFF" fill-rule="evenodd">
                            <rect width="35" height="3" rx="2"/>
                            <rect y="18" width="35" height="3" rx="2"/>
                            <rect y="9" width="35" height="3" rx="2"/>
                        </g>
                    </svg>
                </div>

                <div class="navItem" id="headerLogo">
                    <img src="{{ asset("assets/".Config::get("app.logo_over_white")) }}"/>
                </div>
                <div class="navItem navOptions">
                    <!--  <button>upgrade</button> -->
                    <div class="navLang">
                        <a href="/lang/en">{!! (App::getLocale() == "en") ? "<strong>EN</strong>" : "EN" !!}</a>
                        <span>|</span>
                        <a href="/lang/fr">{!! (App::getLocale() == "fr") ? "<strong>FR</strong>" : "FR" !!}</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>


    @yield("content")


    <footer>


    </footer>


    <!-- SIDE MENU  Needs to stay below FOOTER and outsode of O-wrapper  -->
</div> <!-- End of O-wrapper -->
<nav id="c-menu--push-left" class="c-menu c-menu--push-left">
    <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg">
        <title>
            Close Icon
        </title>
        <path class="closeIcon" d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z" fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
    </svg>
    <div class="profile">
        <img src="/{{ Helper::image(Auth::check() ? Auth::user()->thumb : "") }}" alt="profile image">
        <h2>{{ Auth::check() ? Auth::user()->firstName : "" }}</h2>
        <h4>{{ Auth::check() ? Auth::user()->lastName : "" }}</h4>
    </div>
    <ul class="c-menu__items">
        <div class="menu-section">
            <li class="c-menu__item">
                <a href="{{ Lang::get("routes./Trainee/Profile") }}" class="c-menu__link">{{ Lang::get("content.CMProfile") }}</a>
            </li>
        </div>
        <div class="menu-section">
            <li class="c-menu__item">
                <a href="{{ Lang::get("routes./Trainee/Workouts") }}" class="c-menu__link">{{ Lang::get("content.CMWorkouts") }}</a>
            </li>
        </div>
        <div class="menu-section">
            <li class="c-menu__item">
                <a href="javascript:void(Tawk_API.toggle())" class="c-menu__link">{{ Lang::get("content.Support") }}</a>
            </li>
            <li class="c-menu__item">
                <a href="javascript:void(0)" onclick="moveFeedbackUp();" class="c-menu__link">{{ Lang::get("content.CMSendFeedback") }}</a>
            </li>
            <li class="c-menu__item logout">
                <a href="{{ Lang::get("routes./logout") }}" class="c-menu__link">{{ Lang::get("content.Logout") }}</a>
            </li>
        </div>
    </ul>
    <div class="socialIcons_container">
        <p>{{ Lang::get("content.CMShare") }}</p>
        <div class="socialIcons">
            <a href="https://www.instagram.com/trainerworkout/">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        InstagramSocialIcon
                    </title>
                    <path d="M23.977 1.023C23.352.398 22.603.085 21.73.085H3.27c-.873 0-1.622.313-2.247.938C.397 1.648.085 2.397.085 3.27v18.46c0 .873.312 1.622.938 2.247.625.625 1.374.938 2.247.938h18.46c.873 0 1.622-.313 2.247-.938.625-.625.938-1.374.938-2.247V3.27c0-.873-.313-1.622-.938-2.247zM9 9.033c.976-.943 2.148-1.415 3.516-1.415 1.38 0 2.557.472 3.532 1.415.976.943 1.463 2.082 1.463 3.42 0 1.335-.486 2.475-1.462 3.417-.975.943-2.152 1.415-3.532 1.415-1.368 0-2.54-.472-3.516-1.415-.975-.943-1.463-2.082-1.463-3.418 0-1.337.488-2.476 1.463-3.42zm13.102 12.035c0 .28-.097.514-.29.703a.963.963 0 0 1-.696.284H3.836a.954.954 0 0 1-.704-.283.954.954 0 0 1-.283-.702V10.592h2.28a6.96 6.96 0 0 0-.325 2.118c0 2.07.755 3.834 2.263 5.295 1.51 1.46 3.325 2.19 5.448 2.19a7.77 7.77 0 0 0 3.88-1.002 7.546 7.546 0 0 0 2.813-2.724 7.162 7.162 0 0 0 1.034-3.76 6.96 6.96 0 0 0-.324-2.118h2.183v10.476zm0-14.436c0 .312-.107.577-.323.792-.217.216-.48.323-.793.323h-2.813c-.312 0-.577-.107-.792-.323a1.077 1.077 0 0 1-.323-.792V3.965c0-.302.106-.563.322-.784.215-.22.48-.33.792-.33h2.813c.312 0 .576.11.792.33.215.222.322.483.322.785v2.667z"
                          fill="#FFF" fill-rule="evenodd"/>
                </svg>
            </a>
            <a href="https://www.facebook.com/tworkout">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        FcaebookSocialIcon
                    </title>
                    <path d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-2.337 11.406h-2.83V23.12H14.15V12.856h-2.12v-3.54h2.12V7.197c0-1.52.355-2.668 1.066-3.444.71-.776 1.885-1.164 3.524-1.164h2.83v3.54h-1.78c-.603 0-.988.106-1.155.316-.167.21-.25.58-.25 1.107v1.762h3.2l-.372 3.54z"
                          fill="#FFF" fill-rule="evenodd"/>
                </svg>
            </a>
            <a href="https://www.twitter.com/trainerworkout">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        TwitterSocialIcon
                    </title>
                    <path d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-4.47 8.19c.01.096.015.24.015.435 0 .906-.132 1.814-.396 2.724a9.755 9.755 0 0 1-1.213 2.618 10.193 10.193 0 0 1-1.948 2.215c-.755.64-1.66 1.153-2.716 1.535a9.882 9.882 0 0 1-3.395.574 9.526 9.526 0 0 1-5.206-1.52c.26.033.528.05.808.05 1.563 0 2.97-.486 4.22-1.456a3.223 3.223 0 0 1-1.965-.68 3.45 3.45 0 0 1-1.204-1.68c.28.043.49.064.63.064.237 0 .512-.042.825-.13-.787-.15-1.45-.54-1.99-1.17a3.228 3.228 0 0 1-.807-2.158v-.033c.572.268 1.11.408 1.618.42-.98-.658-1.47-1.6-1.47-2.83 0-.603.155-1.174.467-1.713A9.623 9.623 0 0 0 8.46 9.412a9.61 9.61 0 0 0 3.91 1.05 3.122 3.122 0 0 1-.08-.775c0-.937.33-1.737.994-2.4a3.272 3.272 0 0 1 2.4-.995c.992 0 1.817.356 2.474 1.068a6.753 6.753 0 0 0 2.166-.825c-.27.83-.77 1.46-1.503 1.89.7-.096 1.353-.28 1.957-.548A6.6 6.6 0 0 1 19.08 9.64z"
                          fill="#FFF" fill-rule="evenodd"/>
                </svg>
            </a>
            <a href="https://www.trainerworkout.com/blog">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        TWSocialIcon
                    </title>
                    <path d="M23.464 1.366C22.554.456 21.457 0 20.174 0H4.656c-1.283 0-2.38.455-3.29 1.366C.456 2.276 0 3.373 0 4.656v15.518c0 1.283.455 2.38 1.366 3.29.91.91 2.007 1.366 3.29 1.366h15.518c1.283 0 2.38-.455 3.29-1.366.91-.91 1.366-2.007 1.366-3.29V4.656c0-1.283-.456-2.38-1.366-3.29zM5.01 11.95c.493 0 .83-.168 1.162-.343-1.49.283-2.96-.757-3.172-2.493l2.467-.037.104-.653L7.23 7h1.426L8.32 9.013s1.13-.035 1.954 0c.506.064 1.1.25 1.345.543.307.294.642.914.803 1.917.063.31.167.89.208 1.298l1.66-3.624c.536-.257 1.744-.39 2.823-.032l.524 4.216 3-4.906 1.06-.362.363 1.052-3.86 8.522c-.945.302-1.93.216-2.497 0l-.71-4.25s-1.85 3.837-2.102 4.25c-.857.302-2.09.183-2.48 0 0-.355 0-4.19-.194-4.793 0 0-.104-1.126-.767-1.236H7.902s-.62 3.37-.548 3.48c.05.152.14.17.228.246.36.072.44.136 1.777-.196v2.074c-.768.498-1.755.96-3.38.728-.902-.164-1.584-.64-1.624-1.847.07-.906.334-2.066.653-4.14z"
                          fill="#FFF" fill-rule="evenodd"/>
                </svg>
            </a>
        </div>
    </div>
</nav><!-- /c-menu push-left -->
<div id="c-mask" class="c-mask"></div><!-- /c-mask -->


<!-- FEEDBACK OVERLAY -->

<div class="fb_overlay">
    <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg"
         onclick="moveFeedbackDown();">
        <title>
            Close Icon
        </title>
        <path class="closeIcon"
              d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z"
              fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
        </path>
    </svg>
    <div class="feedbackContainer">
        <h1>{{ Lang::get("content.feedback/ThankYou") }}</h1>
        <h2>{{ Lang::get("content.feedback/1") }}</h2>
        {{ Form::open(array('url' => Lang::get('routes./Feedback'))) }}
        <label for="feedback">{{ Lang::get("content.feedback") }}</label>
        <textarea type="text" placeholder="{{ Lang::get("content.feedback/2") }}" name="feedback"
                  id="feedback"></textarea>
        <div class="btn_container">
            <button>{{{ Lang::get("content.Send") }}}</button>
        </div>
        {{ Form::close() }}
    </div>
</div>


<!-- End of Side Mneu -->

</body>
</html>


<!-- Bootstrap Core JavaScript -->
{{ HTML::script(asset('assets/js/bootstrap.min.js')) }}

{{ HTML::script(asset('assets/lang/'.App::getLocale().'/jsWords.js')) }}

<!-- Home Page Javascript -->
{{ HTML::script(asset('assets/js/global.js')) }}
<!-- Menu Toggleing JavaScript -->
{{ HTML::script(asset('assets/js/menu.js')) }}

{{ HTML::script(asset('assets/fw/addtohomescreen/src/addtohomescreen.js')) }}


@yield('scripts')

@if($errors->any())
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
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/583dad9f73e3d85bf11b9b76/default';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    <script>
        @if(Auth::check())
            Tawk_API.visitor = {
            name: '{{ Auth::user()->getCompleteName() }}',
            email: '{{ Auth::user()->email }}'
        };
        @endif
    </script>
@endif






<script type="text/javascript">


    /**
     * Push right instantiation and action.
     //  */
    // var pushRight = new Menu({
    //   wrapper: '#o-wrapper',
    //   type: 'push-right',
    //   menuOpenerClass: '.c-button',
    //   maskId: '#c-mask'
    // });

    // var pushRightBtn = document.querySelector('#c-button--push-right');

    // pushRightBtn.addEventListener('click', function(e) {
    //   e.preventDefault;
    //   pushRight.open();
    // });


    /**
     * Push left instantiation and action.
     */
    var pushLeft = new Menu({
        wrapper: '#o-wrapper',
        type: 'push-left',
        menuOpenerClass: '.c-button',
        maskId: '#c-mask'
    });

    var pushLeftBtn = document.querySelector('#c-button--push-left');

    pushLeftBtn.addEventListener('click', function (e) {
        e.preventDefault;
        pushLeft.open();
    });

    function closeMenu() {
        pushLeft.close();
    }


    //Open and Close the feedback Form.
    function moveFeedbackUp() {
        closeMenu();
        $(".fb_overlay").addClass("fb_up");

    }

    function moveFeedbackDown() {
        closeMenu();
        $(".fb_overlay").removeClass("fb_up");
    }


    //ALL AJAX FORMS SAVE
    $("body").on("click", ".ajaxSaveNewsletter", function (event) {
        //alert(1);

        var handler = $(this);
        tForm = $(this).closest("form");
        widget = $(this).attr("widget");
        tForm.submit(function (e) {
                e.preventDefault(); //STOP default action
                e.stopImmediatePropagation();
                //var postData = $(this).serializeArray();
                var formURL = $(this).attr("action");
                var preload;
                $.ajax({
                    url: formURL,
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        //preLoad = showLoadWithElement(handler);
                    },
                    success: function (data, textStatus, jqXHR) {
                        //hideLoadWithElement(preLoad);
                        successMessage(data);
                        //upAndClearAdd();

                        //widgetsToReload.push(widget);
                        //refreshWidgets();
                        return false;

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        //hideLoadWithElement(preLoad);
                        errorMessage(jqXHR.responseText);
                        return false;
                    },
                    statusCode: {
                        500: function () {
                            if (jqXHR.responseText != "") {
                                errorMessage(jqXHR.responseText);
                            }
                        }
                    }
                });
            }
        );
    });

    $(document).ready(function () {
        addToHomescreen({
            startDelay: 5,
            skipFirstVisit: true,
            maxDisplayCount: 1
        });
    });
</script>
