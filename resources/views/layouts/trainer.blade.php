<!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 no-js" lang="en">
<![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 no-js" lang="en">
<![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 no-js" lang="en">
<![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 no-js" lang="en">
<![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
@php
    use App\Http\Libraries\Helper;
@endphp
<head>
    <meta charset="utf-8">
    @yield("header")
    <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <!--  Mobile Viewport Fix
       j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag
       device-width : Occupy full width of the screen in its current orientation
       initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
       maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width
       -->
    <meta name="author" content="trainer-workout.com"/>
    <meta name="robots" content="all"/>
    <meta name="distribution" content="global"/>
    <meta name="resource-type" content="document"/>
    <meta name="language" content="en-us"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="copyright" content="trainer-workout.com Copyright 2013. All Rights Reserved.">
    <meta name="viewport" content="initial-scale=1.0, user-scalable = no, width = device-width">
    <link rel="shortcut icon" href="{{asset('assets/img/favicon.ico')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/fw/addtohomescreen/style/addtohomescreen.css')}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{asset('assets/img/apple-touch-icon.png')}}">
    {{--
    <link rel="manifest" href="/manifest.json">
    --}}
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/img/apple-touch-icon.png')}}"/>
    <!-- The is the icon for iOS's web Clip.
       - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
       - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
       - Transparency is not recommended (iOS will put a black BG behind the icon) -->
    <meta name="google-site-verification" content="">
    <meta name="title" content="">
    <meta name="description" content="">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <!-- {{ HTML::script(asset('assets/js/jquery.js')) }} -->
    <!-- AJAX SETUP -->
    <script type="text/javascript">
        $.ajaxSetup({
            header: {
                'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content')
            }
        })
    </script>
    <!-- DataTable -->
    <?php
    if (isset($table)) {
        echo "<link media='all' type='text/css' rel='stylesheet'href='//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css'>";
    }
    ?>
    {{ HTML::style(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/fw/datapicker/jquery.ui.timepicker.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/libs/select2/select2.min.css').ASSET_VERSION) }}
    {{ HTML::script(asset('assets/js/modernizr_touch.js'.ASSET_VERSION)) }}
    @yield("headerScripts")
    <!-- Open Sans Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,800'
          rel='stylesheet' type='text/css'>
    @yield("headerExtra")
    {{ HTML::style(asset('assets/css/Trainer/mobileInnerstyle.css'.ASSET_VERSION)) }}
    {{ HTML::style(asset('assets/css/lang/styles_'.Config::get('app.locale').'.css'.ASSET_VERSION)) }}
    {{-- Diego Test --}}


    @if(Config::get("app.whitelabel") != "default")
            <?php $whitelabel = "ymca"; ?>
    @endif
    @if(Config::get("app.whitelabel") != "default")
        {!! HTML::style(Config::get("app.whitelabel_css_trainer")) !!}
    @endif
    <style>
        .select2-container{
            width: 100% !important;
        }
    </style>
</head>
<body class="trainer">
    <div id="o-wrapper" class="o-wrapper">
        <div class="systemMessages"></div>
        <div class="main_header" id="main_header">
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
                        <img src="{{asset('assets/')}}{{ Config::get("app.logo_header") }}"/>
                    </div>
                    <div class="navItem navOptions">
                        @if((Auth::user()->membership && Auth::user()->membership->membershipId == Config::get("constants.defaultMembership")) or (!Auth::user()->membership))
                            <form action="{{ Lang::get("routes./UpgradePlan") }}">
                                <button type="submit" value="Upgrade">{{ Lang::get("content.upgrade") }}</button>
                            </form>
                        @endif
                        <div class="navLang">
                            <a href="/lang/en">{!! (App::getLocale() == "en") ? "<strong>EN</strong>" : "EN" !!}</a>
                            <span>|</span>
                            <a href="/lang/fr">{!! (App::getLocale() == "fr") ? "<strong>FR</strong>" : "FR" !!}</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        @if(Session::has("originalUser"))
            <div class="exitAccess">
                <div class="upgradePlan">
                    <a id="exitAccess" href="{{ Lang::get('routes./Trainer/EmployeeManagement/PersonifyBack') }}">
                        <svg width="16" height="16" viewBox="0 0 16 16" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Exit Employee View
                            </title>
                            <path
                                d="M11.255 4.235c0 .126.048.25.143.347l2.93 2.928H4.243a.49.49 0 0 0 0 .98h10.083l-2.938 2.938a.49.49 0 0 0 0 .693c.19.193.5.193.692 0l3.775-3.773a.498.498 0 0 0 .106-.16c0-.003.002-.006.003-.008a.485.485 0 0 0 0-.36l-.003-.006a.495.495 0 0 0-.107-.16L12.09 3.888a.49.49 0 0 0-.835.345M8.49 15.51a.49.49 0 0 0-.49-.49H.98V.98H8A.49.49 0 0 0 8 0H.49A.49.49 0 0 0 0 .49v15.02c0 .27.22.49.49.49H8c.27 0 .49-.22.49-.49"
                                fill="#FFF" fill-rule="evenodd"/>
                        </svg>
                        {!! Lang::get("content.exitEmployee") !!}
                    </a>
                </div>
            </div>
        @endif
        @yield("content")
    </div>
    <!-- End of O-wrapper -->
    <!-- SIDE MENU  Needs to stay below FOOTER and outsode of O-wrapper  -->
    <nav id="c-menu--push-left" class="c-menu c-menu--push-left">
        <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg">
            <title>
                Close Icon
            </title>
            <path class="closeIcon"
                  d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z"
                  fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
        </svg>
        <div class="profile">
            <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
            <h2>{!! Auth::user()->firstName !!}</h2>
            <h4>{!! Auth::user()->lastName !!}</h4>
        </div>
        <ul class="c-menu__items">
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Workouts")."/".Helper::formatURLString(Auth::user()->firstName.Auth::user()->lastName) }}" class="c-menu__link menu_workouts">{{ Lang::get("content.TWorkouts") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Exercises") }}" class="c-menu__link menu_exercises">{{ Lang::get("content.TExercises") }}</a>
                </li>
            </div>
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Clients") }}" class="c-menu__link menu_clients">{{ Lang::get("content.TClients") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get('routes./Trainer/Reports/WorkoutsPerformanceClients') }}" class="c-menu__link menu_clientsReports">{{ Lang::get("content.TClientReport") }}</a>
                </li>
                <li class="c-menu__item">
                    <a href="{{ Lang::get('routes./Trainer/Plans') }}" class="c-menu__link menu_myPlans">{{ Lang::get("content.MyPlans") }}</a>
                </li>
            </div>
            
            @if(Auth::user()->group and (Auth::user()->group->role == "Owner" or Auth::user()->group->role == "Admin"))
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./employeeManagement") }}" class="c-menu__link menu_employee">{{ Lang::get("content.EmployeeManagement") }}</a>
                </li>
            </div>
            @endif
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="{{ Lang::get("routes./Trainer/Profile") }}" class="c-menu__link menu_profile">{{ Lang::get("content.TProfile") }}</a>
                </li>
                @if(!(Auth::user()->group and Auth::user()->group->role == "Member"))
                    <li class="c-menu__item">
                        <a href="{{ Lang::get("routes./MembershipManagement") }}" class="c-menu__link menu_membership">{{ Lang::get("content.TMembership") }}</a>
                    </li>
                @endif
            </div>
            <div class="menu-section">
                <li class="c-menu__item">
                    <a href="javascript:void(0);" onclick="moveFeedbackUp();" class="c-menu__link">{{ Lang::get("content.CMSendFeedback") }}</a>
                </li>
                @if(Auth::user()->userType == "Trainer" )
                    <li class="c-menu__item logout">
                        <a onclick="deleteAccount();" href="javascript:void(0);" class="c-menu__link">{{ Lang::get("content.DeleteAccount") }}</a>
                    </li>
                @endif
                <li class="c-menu__item logout">
                    <a onclick="deleteIndexedDatabase();" href="{{ Lang::get("routes./logout") }}" class="c-menu__link">{{ Lang::get("content.Logout") }}</a>
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
                        <path
                            d="M23.977 1.023C23.352.398 22.603.085 21.73.085H3.27c-.873 0-1.622.313-2.247.938C.397 1.648.085 2.397.085 3.27v18.46c0 .873.312 1.622.938 2.247.625.625 1.374.938 2.247.938h18.46c.873 0 1.622-.313 2.247-.938.625-.625.938-1.374.938-2.247V3.27c0-.873-.313-1.622-.938-2.247zM9 9.033c.976-.943 2.148-1.415 3.516-1.415 1.38 0 2.557.472 3.532 1.415.976.943 1.463 2.082 1.463 3.42 0 1.335-.486 2.475-1.462 3.417-.975.943-2.152 1.415-3.532 1.415-1.368 0-2.54-.472-3.516-1.415-.975-.943-1.463-2.082-1.463-3.418 0-1.337.488-2.476 1.463-3.42zm13.102 12.035c0 .28-.097.514-.29.703a.963.963 0 0 1-.696.284H3.836a.954.954 0 0 1-.704-.283.954.954 0 0 1-.283-.702V10.592h2.28a6.96 6.96 0 0 0-.325 2.118c0 2.07.755 3.834 2.263 5.295 1.51 1.46 3.325 2.19 5.448 2.19a7.77 7.77 0 0 0 3.88-1.002 7.546 7.546 0 0 0 2.813-2.724 7.162 7.162 0 0 0 1.034-3.76 6.96 6.96 0 0 0-.324-2.118h2.183v10.476zm0-14.436c0 .312-.107.577-.323.792-.217.216-.48.323-.793.323h-2.813c-.312 0-.577-.107-.792-.323a1.077 1.077 0 0 1-.323-.792V3.965c0-.302.106-.563.322-.784.215-.22.48-.33.792-.33h2.813c.312 0 .576.11.792.33.215.222.322.483.322.785v2.667z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
{{--                <a href="https://www.facebook.com/tworkout">--}}
{{--                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">--}}
{{--                        <title>--}}
{{--                            FcaebookSocialIcon--}}
{{--                        </title>--}}
{{--                        <path--}}
{{--                            d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-2.337 11.406h-2.83V23.12H14.15V12.856h-2.12v-3.54h2.12V7.197c0-1.52.355-2.668 1.066-3.444.71-.776 1.885-1.164 3.524-1.164h2.83v3.54h-1.78c-.603 0-.988.106-1.155.316-.167.21-.25.58-.25 1.107v1.762h3.2l-.372 3.54z"--}}
{{--                            fill="#FFF" fill-rule="evenodd"/>--}}
{{--                    </svg>--}}
{{--                </a>--}}
                <a href="https://www.twitter.com/trainerworkout">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            TwitterSocialIcon
                        </title>
                        <path
                            d="M23.55 1.45C22.637.54 21.54.086 20.26.086H4.74C3.46.085 2.363.54 1.45 1.45.54 2.363.086 3.46.086 4.74v15.52c0 1.282.455 2.378 1.366 3.29.912.91 2.008 1.365 3.29 1.365h15.52c1.282 0 2.378-.455 3.29-1.366.91-.912 1.365-2.008 1.365-3.29V4.74c0-1.282-.456-2.378-1.366-3.29zm-4.47 8.19c.01.096.015.24.015.435 0 .906-.132 1.814-.396 2.724a9.755 9.755 0 0 1-1.213 2.618 10.193 10.193 0 0 1-1.948 2.215c-.755.64-1.66 1.153-2.716 1.535a9.882 9.882 0 0 1-3.395.574 9.526 9.526 0 0 1-5.206-1.52c.26.033.528.05.808.05 1.563 0 2.97-.486 4.22-1.456a3.223 3.223 0 0 1-1.965-.68 3.45 3.45 0 0 1-1.204-1.68c.28.043.49.064.63.064.237 0 .512-.042.825-.13-.787-.15-1.45-.54-1.99-1.17a3.228 3.228 0 0 1-.807-2.158v-.033c.572.268 1.11.408 1.618.42-.98-.658-1.47-1.6-1.47-2.83 0-.603.155-1.174.467-1.713A9.623 9.623 0 0 0 8.46 9.412a9.61 9.61 0 0 0 3.91 1.05 3.122 3.122 0 0 1-.08-.775c0-.937.33-1.737.994-2.4a3.272 3.272 0 0 1 2.4-.995c.992 0 1.817.356 2.474 1.068a6.753 6.753 0 0 0 2.166-.825c-.27.83-.77 1.46-1.503 1.89.7-.096 1.353-.28 1.957-.548A6.6 6.6 0 0 1 19.08 9.64z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
                <a href="https://www.trainer-workout.com/blog">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="https://www.w3.org/2000/svg">
                        <title>
                            TWSocialIcon
                        </title>
                        <path
                            d="M23.464 1.366C22.554.456 21.457 0 20.174 0H4.656c-1.283 0-2.38.455-3.29 1.366C.456 2.276 0 3.373 0 4.656v15.518c0 1.283.455 2.38 1.366 3.29.91.91 2.007 1.366 3.29 1.366h15.518c1.283 0 2.38-.455 3.29-1.366.91-.91 1.366-2.007 1.366-3.29V4.656c0-1.283-.456-2.38-1.366-3.29zM5.01 11.95c.493 0 .83-.168 1.162-.343-1.49.283-2.96-.757-3.172-2.493l2.467-.037.104-.653L7.23 7h1.426L8.32 9.013s1.13-.035 1.954 0c.506.064 1.1.25 1.345.543.307.294.642.914.803 1.917.063.31.167.89.208 1.298l1.66-3.624c.536-.257 1.744-.39 2.823-.032l.524 4.216 3-4.906 1.06-.362.363 1.052-3.86 8.522c-.945.302-1.93.216-2.497 0l-.71-4.25s-1.85 3.837-2.102 4.25c-.857.302-2.09.183-2.48 0 0-.355 0-4.19-.194-4.793 0 0-.104-1.126-.767-1.236H7.902s-.62 3.37-.548 3.48c.05.152.14.17.228.246.36.072.44.136 1.777-.196v2.074c-.768.498-1.755.96-3.38.728-.902-.164-1.584-.64-1.624-1.847.07-.906.334-2.066.653-4.14z"
                            fill="#FFF" fill-rule="evenodd"/>
                    </svg>
                </a>
                <a href="https://grewon.slack.com/archives/D07R2F9PRFT/p1745929287871669">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M407 0H105C47.103 0 0 47.103 0 105v302c0 57.897 47.103 105 105 105h302c57.897 0 105-47.103 105-105V105C512 47.103 464.897 0 407 0zM157.649 394.515c-4.625 8.011-13.046 12.494-21.693 12.495a24.847 24.847 0 0 1-12.458-3.344c-11.938-6.892-16.043-22.212-9.151-34.15l4.917-8.516h57.735l-19.35 33.515zM110.5 341c-13.785 0-25-11.215-25-25s11.215-25 25-25h49.178l67.454-116.834-18.281-31.664c-6.892-11.938-2.788-27.258 9.15-34.151h.001c11.938-6.892 27.258-2.786 34.15 9.151l3.848 6.665 3.848-6.664c6.895-11.939 22.215-16.043 34.15-9.151 5.783 3.339 9.92 8.73 11.648 15.18 1.729 6.45.841 13.188-2.498 18.971L217.413 291h54.079l28.868 50H110.5zm291 0h-20.311l16.463 28.515c6.893 11.937 2.788 27.257-9.149 34.15-3.853 2.224-8.129 3.361-12.461 3.361-2.172 0-4.356-.285-6.511-.863-6.451-1.729-11.842-5.866-15.181-11.65l-86.804-150.348 28.867-50L352.322 291H401.5c13.785 0 25 11.215 25 25s-11.215 25-25 25z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path></g></svg>
                </a>
                <a href="https://play.google.com/apps/testing/com.app.trainerworkout">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" x="0" y="0" viewBox="0 0 32 32" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m17 14.5 4.2-4.5L4.9 1.2c-.1-.1-.3-.1-.6-.2zM23 21l5.9-3.2c.7-.4 1.1-1 1.1-1.8s-.4-1.5-1.1-1.8L23 11l-4.7 5zM2.4 1.9c-.3.3-.4.7-.4 1.1v26c0 .4.1.8.4 1.2L15.6 16zM17 17.5 4.3 31c.2 0 .4-.1.6-.2L21.2 22z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path></g></svg>
                </a>
            </div>
        </div>
    </nav>
    <!-- /c-menu push-left -->
    <nav class="p-menu">
    </nav>
    <div id="c-mask" class="c-mask"></div>
    <!-- /c-mask -->
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
    <div class='loader-bg'>
        <img src='{{asset('assets/img/tw-gif.gif')}}'>
        <button onclick="hideTopLoader()" class="btn" style="color: #ffffff;position: absolute;right: 0;top: 0;padding: 10px;font-weight: bold;background: transparent;border: none;">X</button>
    </div>
</body>

<!-- Menu Toggleing JavaScript -->
{{ HTML::script(asset('assets/js/menu.js'.ASSET_VERSION)) }}
<!-- Bootstrap Core JavaScript -->
{{ HTML::script(asset('assets/lang/'.App::getLocale().'/jsWords.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/bootstrap.min.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/ckeditor/ckeditor.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/fancybox/source/jquery.fancybox.pack.js?v=2.1.5')) }}
{{ HTML::script(asset('assets/fw/datapicker/jquery.ui.timepicker.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/touchpunch.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/global.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/js/widgets.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/libs/select2/select2.min.js').ASSET_VERSION) }}
{{ HTML::script(asset('assets/fw/lightbox/js/lightbox.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/addtohomescreen/src/addtohomescreen.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/templates/exerciseList.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/templates/workoutBuilder.js'.ASSET_VERSION)) }}
<!-- CHOSEN SELCT BOX -->
{{ HTML::script(asset('assets/fw/chosen_v1/chosen.jquery.js'.ASSET_VERSION)) }}
{{ HTML::script(asset('assets/fw/chosen_v1/docsupport/prism.js'.ASSET_VERSION)) }}
<script type="text/javascript">
    $(document).ready(function () {
        var config = {
            '.chosen-select': {search_contains: true},
            '.chosen-select-deselect': {allow_single_deselect: true},
            '.chosen-select-no-single': {disable_search_threshold: 10},
            '.chosen-select-no-results': {no_results_text: 'Oops, nothing found!'},
            '.chosen-select-width': {width: "200px;"}
        }
        for (var selector in config) {
            $(selector).chosen(config[selector]);
        }

        $('.select2-select').select2({
            searching: true,
            dropdownParent: $('body')
        })
    });
</script>
<!-- DataTables JavaScript -->
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script type="text/javascript">
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

    function deleteAccount() {
        if(confirm("Are You Sure You Want To Delete Your Account ?")){
            $.ajax({
                url: "{{Lang::get("routes./delete-account")}}/{{auth()->user()->id}}",
                type: "DELETE",
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                    deleteIndexedDatabase();
                    window.location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText + " " + errorThrown);
                },
            });
        }
    }
</script>
@yield('scripts')
@if($errors->any())
        <?php $message = ""; ?>
    @foreach ($errors->all() as $error)
        <?php $message .= $error . "</br>"; ?>
    @endforeach
    <script>errorMessage("{!! $message !!}")</script>
@endif
@if(Session::has("message"))
    <script>successMessage("{!! Session::get("message") !!}")</script>
    @php session()->forget('message') @endphp
@endif
@if(Session::has("error"))
    <script>errorMessage("{!! Session::get("error") !!}")</script>
@endif
<script>
    @if(Config::get("app.debug"))
        debug = true;
    @else
        debug = false;
    @endif
</script>
<?php

?>
@if(!Config::get("app.debug"))
    <script type="text/javascript">
        //Check if location is on pages we do not want to show the launcher
        var onPage = false;
        if ((window.location.href.indexOf("CreateWorkout") > -1) || (window.location.href.indexOf("CreerWorkout") > -1)) {
            onPage = true;
        } else {
            onPage = false;
        }
    </script>
    <script>
        {{--// window.intercomSettings = {--}}
        {{--//   //The current logged in user's full name--}}
        {{--//   name: "{{{ Auth::user()->getCompleteName() }}}",--}}
        {{--//   //The current logged in user's email address.--}}
        {{--//   email: "{{{ Auth::user()->email }}}",--}}
        {{--//   user_id: "{{{ Auth::user()->id }}}",--}}
        {{--//   //The current logged in user's sign-up date as a Unix timestamp.--}}
        {{--//   created_at: {{ Helper::dateToUnix(Auth::user()->created_at) }},--}}
        {{--//   //UserType--}}
        {{--//   "userType": "trainer",--}}
        {{--//   //Language--}}
        {{--//   "lang": "{{{ Auth::user()->lang }}}",--}}
        {{--//   //Memberhsip type--}}
        {{--//   "membership_plan": "{{{ (Auth::user()->getTrainerWorkoutMembership() and Auth::user()->getTrainerWorkoutMembership()->membership) ? Auth::user()->getTrainerWorkoutMembership()->membership->name : "" }}}",--}}
        {{--//   // Nb of clients--}}
        {{--//   "number_of_clients": "{{{ Auth::user()->getNumberOfClients() }}}",--}}
        {{--//   //Nb of workouts in account--}}
        {{--//   "number_of_workouts": "{{{ Auth::user()->getNumberOfWorkouts() }}}",--}}
        {{--//   //Nb of exercise created--}}
        {{--//   "number_of_exercises": "{{{ Auth::user()->getNumberOfExercises() }}}",--}}

        {{--//   // HIDE INTERCOM CHAT ICON ON Create Workout page--}}
        {{--//   "hide_default_launcher": onPage,--}}

        {{--//   //Where did the user come from--}}
        {{--//   <?php--}}
        {{--         //     $params = explode("&",Auth::user()->marketing);--}}
        {{--         //     foreach($params as $param){--}}
        {{--         //       $pa = explode("=",$param);--}}
        {{--         //       if($pa > 1 and count($pa) > 1){--}}
        {{--         //         echo '"'.$pa[0].'":"'.$pa[1].'",';--}}
        {{--         //       }--}}
        {{--         //     }--}}
        {{--         //     ?>--}}
        {{--//   //Our app ID--}}
        {{--//   app_id: "af0obxyk",--}}
        {{--//   //Class click that launches our intercom messenger--}}
        {{--//   custom_launcher_selector: '#intercomWindow',--}}
        {{--// };--}}

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
    <script>
        (function () {
            var w = window;
            var ic = w.Intercom;
            if (typeof ic === "function") {
                ic('reattach_activator');
                ic('update', intercomSettings);
            } else {
                var d = document;
                var i = function () {
                    i.c(arguments)
                };
                i.q = [];
                i.c = function (args) {
                    i.q.push(args)
                };
                w.Intercom = i;

                function l() {
                    var s = d.createElement('script');
                    s.type = 'text/javascript';
                    s.async = true;
                    s.src = 'https://widget.intercom.io/widget/af0obxyk';
                    var x = d.getElementsByTagName('script')[0];
                    x.parentNode.insertBefore(s, x);
                }

                if (w.attachEvent) {
                    w.attachEvent('onload', l);
                } else {
                    w.addEventListener('load', l, false);
                }
            }
        })()
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '400688060094236', {
            em: '{{{ Auth::user()->email }}}'
        });
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=400688060094236&ev=PageView&noscript=1"/></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    {{ HTML::script(asset('assets/js/thirdParty.js')) }}
@endif
<script>
    $(document).ready(function () {
        addToHomescreen({
            startDelay: 5,
            skipFirstVisit: false,
            maxDisplayCount: 1,
        });
    });

    function openDatabase() {
        return new Promise((resolve, reject) => {
            let request = indexedDB.open("trainer_workout", 1);

            request.onupgradeneeded = function(event) {
                let db = event.target.result;
                if (!db.objectStoreNames.contains("users")) {
                    db.createObjectStore("users", { keyPath: "id", autoIncrement: true });
                }
            };
            request.onsuccess = function(event) {
                resolve(event.target.result);
            };
            request.onerror = function(event) {
                reject("Error opening IndexedDB: " + event.target.errorCode);
            };
        });
    }

    function storeEmail(email) {
        openDatabase().then(db => {
            let transaction = db.transaction("users", "readwrite");
            let store = transaction.objectStore("users");
            let clearRequest = store.clear();
            clearRequest.onsuccess = function() {
                let addRequest = store.add({ email: email });
                addRequest.onsuccess = function() {
                    console.log("Email stored successfully.");
                };
                addRequest.onerror = function(event) {
                    console.error("Error storing email: ", event.target.error);
                };
            };
            clearRequest.onerror = function(event) {
                console.error("Error clearing object store: ", event.target.error);
            };
        }).catch(error => console.error(error));
    }

    function deleteIndexedDatabase() {
        let request = indexedDB.deleteDatabase("trainer_workout");
        request.onsuccess = function() {
            console.log("Database deleted successfully.");
        };
        request.onerror = function(event) {
            console.error("Error deleting database: ", event.target.error);
        };
        request.onblocked = function() {
            console.error("Database deletion blocked. Close all connections and try again.");
        };
    }

    // Example usage:
    storeEmail("{{auth()->user()->email}}");
</script>
</html>
